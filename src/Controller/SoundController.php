<?php

namespace App\Controller;

use App\Entity\Sound;
use App\Repository\SoundRepository;
use App\Service\MP3File;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

class SoundController extends AbstractController
{
    /**
     * @Route("/songs", name="sound", methods={"POST"})
     */
    public function index(Request $request, MP3File $MP3File): Response
    {
        $audio = $request->files->get('audio');
        $cover = $request->files->get('cover');
        $sound = new Sound();
        if($audio)
        {
            $origin     = pathinfo($audio->getClientOriginalName(),PATHINFO_FILENAME);
            $save       = transliterator_transliterate('Any-Latin;Latin-ASCII;[^A-Za-z0-9_] remove;Lower',$origin);
            $nouveau    = $save.uniqid().'.'.$audio->guessClientExtension();
            try {
                $path = $audio->move($this->getParameter('uploadaudio'),$nouveau);
                $duration =  $MP3File->getDuration($use_cbr_estimate=true,$path->getRealPath());//http://www.npr.org/rss/podcast.php?id=510282
                $sound  ->setUrl($path->getRealPath())
                        ->setType($path->getExtension())
                        ->setName($origin)
                        ->setCover($cover)
                        ->setTime($MP3File->formatTime($duration))
                        ->setCreatedAt(new \DateTimeImmutable());
            } catch (FileException $e) {
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($sound);
            $em->flush();
            return $this->json(['error' => false, 'message' =>"Add song success",'code'=>200], Response::HTTP_CREATED, [], []);

        }
        return $this->json(['error' => true,'code'=>400], Response::HTTP_CREATED, [], []);
    }
    /**
     * @Route("/songs", name="sourceaudio", methods={"GET"})
     */
    public function sourceaudio(TokenStorageInterface $tokenStorage, SoundRepository $songs){
        $token = $tokenStorage->getToken('authenticate');
        if ($token) {
            if ($this->getUser()) {
                if ($this->getUser()->getFinabonnement() > new \DateTimeImmutable()) {
                    $songs = $songs->findAll();
                    return $this->json(['error' => false, 'songs'=>$songs ,'code'=>200], Response::HTTP_CREATED, [], []);
                }
                else{
                    return $this->json(['error' => true, 'message' =>"Votre abonnement permet pas d'accéder à la ressource",'code'=>403], Response::HTTP_CREATED, [], []);
                }
            } 
        }else{
            return new JsonResponse(['error'=> true,'message'=>"Votre token n'est pas correct", 'code'=>401]);
        }
    }
    /**
     * @Route("/songs/{id}", name="sourceaudiobyid", methods={"GET"})
     */
    public function sourceaudiobyid($id,TokenStorageInterface $tokenStorage, SoundRepository $songs){
        $token = $tokenStorage->getToken('authenticate');
        if ($token) {
            if ($this->getUser()) {
                if ($this->getUser()->getFinabonnement() > new \DateTimeImmutable()) {
                    $songs = $songs->find($id);
                    if(is_null($songs))
                    {
                        return $this->json(['error' => false,'songs'=>'Song not found' ,'code'=>200], Response::HTTP_CREATED, [], []);
                    }
                    return $this->json(['error' => false,'songs'=>$songs ,'code'=>200], Response::HTTP_CREATED, [], []);
                }
                else{
                    return $this->json(['error' => true, 'message' =>"Votre abonnement permet pas d'accéder à la ressource",'code'=>403], Response::HTTP_CREATED, [], []);
                }
            }
        }else{
            return new JsonResponse(['error'=> true,'message'=>"Votre token n'est pas correct", 'code'=>401]);
        }
    }
}
