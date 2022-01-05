"{% if is_granted('ROLE_ADMIN')   %}"
var modal = document.getElementById("myModal");
function imageClick(e){
	var modalImg = document.getElementById("img01");
	var captionText = document.getElementById("caption");
	modal.style.display = "block";
	modalImg.src = e.src;
	captionText.innerHTML = e.alt;
	$('.modal_content').removeClass('zoom_in');
	$('body').removeClass('body_fixed');
}
$('.closephoto').click( function(){
	modal.style.display = "none";
	$('body').removeClass('body_fixed');
});

function handleFiles(files) {
	var imageType = /^audio\//;
	for (var i = 0; i < files.length; i++) {
		var file = files[i];
		var sound = document.querySelector("#audioImport");
		document.getElementById("name").value = file.name;
		document.getElementById("time").value = file.time;
		document.getElementById("type").value = file.type;
		if (!imageType.test(file.type)) {
			alert("veuillez sélectionner un audio");
			preview.innerHTML = '';
		}else{
			if(i == 0){
				preview.innerHTML = '';
			}
			var audio = document.getElementById("audioImport");
			audio.file = file;
			var reader = new FileReader();
			reader.onload = ( function(aImg) {
				return function(e) {
					aImg.src = e.target.result;
				};
			})(audio);
			reader.readAsDataURL(file);
		}}

}

function handleFilesEdit(files) {
	var imageType = /^image\//;
	for (var i = 0; i < files.length; i++) {
		var file = files[i];
		alert(file.time);
		if (!imageType.test(file.type)) {
			alert("veuillez sélectionner une image");
			previewEdit.innerHTML = '';
		}else{
			if(i == 0){
				previewEdit.innerHTML = '';
			}
			var img = document.getElementById("imgphotoE");
			img.file = file;
			var reader = new FileReader();
			reader.onload = ( function(aImg) {
				return function(e) {
					aImg.src = e.target.result;

				};
			})(img);
			reader.readAsDataURL(file);
		}}}
function focusError(nonerror){
	$(nonerror).removeClass('has-error');
}
function focusreset(){
	$('*').removeClass('has-error');
}
function notifications(e){
	if( e == false){
		toastr.error("<span style='font-size:13px'>Veuillez remplir les champs corrèctement</span>", '', {timeOut: 6000});
	}
}
function messageerror(){
		toastr.error("<span style='font-size:13px'>Problème lors de suppression de l'Utilisateur</span>", '', {timeOut: 6000});
}
function verificationproduit(){
	var result = true;
	var produit = $('#produit').val();
	var info = $('#info').val();
	var prix = $('#prix').val();
	var photo = $('#photo').val();
	var contact = $('#contact').val();
	var categ = $('#categ').val();
	if ( produit == null || produit == "" || produit.length >= 49 ){
		$('#produit').addClass('has-error');
		result = false;
	}if ( info == null || info == "" || info.length >= 249 ){
		$('#info').addClass('has-error');
		result = false;
	}if ( prix == null || prix == "" || prix.length >= 13 ){
		$('#prix').addClass('has-error');
		result = false;
	}if ( contact == null || contact == "" || contact.length >= 15 ){
		$('#contact').addClass('has-error');
		result = false;
	}if (photo.length == 0){
		$('.label-file').addClass('has-error');
		result = false;
	}if ( categ == "" ){
		$('#categ').addClass('has-error');
		result = false;
	}
	notifications(result);
	return result;
}
function verificationproduitE(){
	var result = true;
	var produit = $('#produitE').val();
	var info = $('#infoE').val();
	var prix = $('#prixE').val();
	var contact = $('#contactE').val();
	var categ = $('#categEdit').val();
	if ( produit == null || produit == "" || produit.length >= 49 ){
		$('#produitE').addClass('has-error');
		result = false;
	}if ( info == null || info == "" || info.length >= 249 ){
		$('#infoE').addClass('has-error');
		result = false;
	}if ( prix == null || prix == "" || prix.length >= 13 ){
		$('#prixE').addClass('has-error');
		result = false;
	}if ( contact == null || contact == "" || contact.length >= 15 ){
		$('#contactE').addClass('has-error');
		result = false;
	}if ( categ == null ){
		$('#categEdit').addClass('has-error');
		result = false;
	}
	notifications(result);
	return result;
}

function verificationadContact(){
	var result = true;
	var email = $('#email').val();
	var phone = $('#phone').val();
	var location = $('#location').val();
	var photo = $('#carte').val();
	if ( email == null || email == "" || email.length >= 99 ){
		$('#email').addClass('has-error');
		result = false;
	}if ( phone == null || phone == "" || phone.length >= 15 ){
		$('#phone').addClass('has-error');
		result = false;
	}if ( location == null || location == "" || location.length >= 60 ){
		$('#location').addClass('has-error');
		result = false;
	}if (photo.length == 0){
		$('.label-file').addClass('has-error');
		result = false;
	}
	notifications(result);
	return result;
}
function verificationadContactE(){
	var result = true;
	var emailemailEdit = $('#emailEdit').val();
	var phoneEdit = $('#phoneEdit').val();
	var locationEdit = $('#locationEdit').val();
	if ( emailEdit == null || emailEdit == "" || emailEdit.length >= 99 ){
		$('#emailEdit').addClass('has-error');
		result = false;
	}if ( phoneEdit == null || phoneEdit == "" || phoneEdit.length >= 15 ){
		$('#phoneEdit').addClass('has-error');
		result = false;
	}if ( locationEdit == null || locationEdit == "" || locationEdit.length >= 60 ){
		$('#locationEdit').addClass('has-error');
		result = false;
	}
	notifications(result);
	return result;
}

function verification(){
	var table = $('#datatable-resposive').DataTable();
	var lengthRows = table.rows().count();
	var trouve = false;
	var result = true;
	var nom_gest = $('#nom_gest').val();
	var email_gest = $('#email_gest').val();
	var numtel_gest = $('#numtel_gest').val();
	var username = $('#username').val();
	var password = $('#password').val();
	var passwordverif = $('#passwordverif').val();
	var photo = $('#photo_gest').val();
	if ( nom_gest.length < 4 || nom_gest.length >= 150 ){
		$('#nom_gest').addClass('has-error');
		result = false;
	}if ( email_gest == null || email_gest == "" || email_gest.length >= 49 ){
		$('#email_gest').addClass('has-error');
		result = false;
	}else
	{
		var i = 0;
		while(i < lengthRows) {
			var email_gestExist = table.cell(i,2).data();
			if( email_gest == email_gestExist ){
				$('#email_gest').addClass('has-error');
				result = false; break;}
			i++;
		}
	}
	if ( numtel_gest.length < 7 || numtel_gest.length >= 15 ){
		$('#numtel_gest').addClass('has-error');
		result = false;
	}

	if ( (username.length < 4 || username.length >= 50) ){
		$('#username').addClass('has-error');
		result = false;
	}else{
		var i = 0;
		while(i < userss.length) {
			if( username == userss[i] ){
				$('#username').addClass('has-error');
				result = false; break;}
			i++;
		}
	}if ( password.length < 8 || password.length > 50 ){
		$('#password').addClass('has-error');
		result = false;
	}if ( password.length < 8 || passwordverif.length > 50 || password != passwordverif ){
		$('#passwordverif').addClass('has-error');
		result = false;
	}
	if (photo.length == 0){
		$('.label-file').addClass('has-error');
		$('#imgPhoto').addClass('has-error');
		result = false;
	}
	notifications(result);
	return result;
}

function verificationEdit(){
	var table = $('#datatable-resposive').DataTable();
	var lengthRows = table.rows().count();
	var trouve = false;
	var result = true;
	var nom_gest = $('#nom_gestEdit').val();
	var email_gest = $('#email_gestEdit').val();
	var ignoreEmail = $('#ignoreEmail').val();
	var numtel_gest = $('#numtel_gestEdit').val();
	var username = $('#usernameEdit').val();
	var ignoreUser = $('#ignoreUser').val();
	var password = $('#passwordEdit').val();
	var passwordverif = $('#passwordverifEdit').val();
	var photo = $('#photo_gestEdit').val();
	if ( nom_gest.length < 4 || nom_gest.length >= 150 ){
		$('#nom_gestEdit').addClass('has-error');
		result = false;
	}if ( email_gest == null || email_gest == "" || email_gest.length >= 49 ){
		$('#email_gestEdit').addClass('has-error');
		result = false;
	}else
	{
		var i = 0;
		while(i < lengthRows) {
			var email_gestExist = table.cell(i,2).data();
			if( email_gest == email_gestExist && ignoreEmail != email_gest){
				$('#email_gestEdit').addClass('has-error');
				result = false; break;}
			i++;
		}
	}
	if ( numtel_gest.length < 7 || numtel_gest.length >= 15 ){
		$('#numtel_gestEdit').addClass('has-error');
		result = false;
	}if ( username.length < 4 || username.length >= 50 ){
		$('#usernameEdit').addClass('has-error');
		result = false;
	}else{
		var i = 0;
		while(i < lengthRows) {
			var userExist = table.cell(i,5).data();
			if( username == userExist && ignoreUser != username ){
				$('#usernameEdit').addClass('has-error');
				result = false; break;}
			i++;
		}
	}
	notifications(result);
	return result;
}
function convertDate(dateString)
{
	var datesplt = dateString.split("/");
	var result = datesplt[2]+"-"+datesplt[1]+"-"+datesplt[0];
	return result;
}
function regexDate(daty){
	var dateElems = daty.split('-');
	var regex =/^(\d{4})(\-\d{2}){2}$/;
	if( regex.test(daty) && dateElems[0] > 2000 && dateElems[0] < 3000 ){
		return true;
	}
	return false;
}
function verificationactions(){
	var result = true;
	var nom_action = $('#nom_action').val();
	var roulement = $('#roulement').val();
	var min_invest=$('#min_invest').val();
	var max_invest=$('#max_invest').val();
	var duree=$('#duree').val();
	var detail_info=$('#detail_info').val();
	var argent = $('#argent').val();


	var debut = $('#debutaction').val();
	if (regexDate(debut) == false )
	{	$('#debutaction').addClass('has-error');
		result = false;}
	var datefin = $('#datefin_action').val();
	if (regexDate(datefin) == false )
	{   $('#datefin_action').addClass('has-error');
		result = false;}

	if ( nom_action.length < 2  || nom_action.length >= 150 ){
		$('#nom_action').addClass('has-error');
		result = false;
	}if ( roulement.length < 1  || roulement.length >= 15 ){
		$('#roulement').addClass('has-error');
		result = false;
	}if ( min_invest.length < 1  || min_invest.length >= 15 ){
		$('#min_invest').addClass('has-error');
		result = false;
	}if ( max_invest.length < 1  || max_invest.length >= 15 ){
		$('#max_invest').addClass('has-error');
		result = false;
	}if ( duree.length < 1  || duree.length >= 15 ){
		$('#duree').addClass('has-error');
		result = false;
	}if ( detail_info.length < 4  || detail_info.length >= 2000 ){
		$('#detail_info').addClass('has-error');
		result = false;
	}if ( argent.length < 1){
		$('#argent').addClass('has-error');
		result = false;
	}
	notifications(result);
	return result;
}

function verificationactionsEdit(){
	var result = true;
	var nom_action = $('#nom_actionEdit').val();
	var roulement = $('#roulementEdit').val();
	var min_invest=$('#min_investEdit').val();
	var max_invest=$('#max_investEdit').val();
	var duree=$('#dureeEdit').val();
	var detail_info=$('#detail_infoEdit').val();
	var argent=$('#argentEdit').val();
	var debut = $('#debutactionEdit').val();
	if (regexDate(debut) == false )
	{	$('#debutactionEdit').addClass('has-error');
		result = false;}
	var datefin = $('#datefin_actionEdit').val();
	if (regexDate(datefin) == false )
	{   $('#datefin_actionEdit').addClass('has-error');
		result = false;}

	if ( nom_action.length < 2  || nom_action.length >= 150 ){
		$('#nom_actionEdit').addClass('has-error');
		result = false;
	}if ( roulement.length < 1  || roulement.length >= 15 ){
		$('#roulementEdit').addClass('has-error');
		result = false;
	}if ( min_invest.length < 1  || min_invest.length >= 15 ){
		$('#min_investEdit').addClass('has-error');
		result = false;
	}if ( max_invest.length < 1  || max_invest.length >= 15 ){
		$('#max_investEdit').addClass('has-error');
		result = false;
	}if ( duree.length < 1  || duree.length >= 15 ){
		$('#dureeEdit').addClass('has-error');
		result = false;
	}if ( detail_info.length < 4  || detail_info.length >= 2000 ){
		$('#detail_infoEdit').addClass('has-error');
		result = false;
	}if ( argent.length < 1 ){
		$('#argentEdit').addClass('has-error');
		result = false;
	}
	notifications(result);
	return result;
}

function verificationcours(){
	var result = true;
	var nom_cours = $('#nom_cours').val();
	var achat = $('#achat').val();
	var vente=$('#vente').val();
	if ( nom_cours.length < 1  || nom_cours.length >= 10 ){
		$('#nom_cours').addClass('has-error');
		result = false;
	}if ( achat.length < 1  || achat.length >= 10 ){
		$('#achat').addClass('has-error');
		result = false;
	}if ( vente.length < 1  || vente.length >= 10 ){
		$('#vente').addClass('has-error');
		result = false;
	}
	notifications(result);
	return result;
}
function verificationcoursEdit(){
	var result = true;
	var nom_cours = $('#nom_coursE').val();
	var achat = $('#achatE').val();
	var vente=$('#venteE').val();
	if ( nom_cours.length < 1  || nom_cours.length >= 10 ){
		$('#nom_coursE').addClass('has-error');
		result = false;
	}if ( achat.length < 1  || achat.length >= 10 ){
		$('#achatE').addClass('has-error');
		result = false;
	}if ( vente.length < 1  || vente.length >= 10 ){
		$('#venteE').addClass('has-error');
		result = false;
	}
	notifications(result);
	return result;
}

function verificationInfo(){
	var result = true;
	var info1 = $('#info1').val();
	var info2 = $('#info2').val();
	var info3 =$('#info3').val();
	var info4 =$('#info4').val();
	var info5 =$('#info5').val();
	var pu1 =$('#pu1').val();
	if ( info1.length < 1  || info1.length >= 1000 ){
		$('#info1').addClass('has-error');
		result = false;
	}if ( info2.length < 1  || info2.length >= 1000 ){
		$('#info2').addClass('has-error');
		result = false;
	}if ( info3.length < 1  || info3.length >= 1000 ){
		$('#info3').addClass('has-error');
		result = false;
	}if ( info4.length < 1  || info4.length >= 1000 ){
		$('#info4').addClass('has-error');
		result = false;
	}if ( info5.length < 1  || info5.length >= 1000 ){
		$('#info5').addClass('has-error');
		result = false;
	}if ( pu1.length < 1  || pu1.length >= 10 ){
		$('#pu1').addClass('has-error');
		result = false;
	}
	notifications(result);
	return result;
}

function verificationInfoEdit(){
	var result = true;
	var info1 = $('#info1E').val();
	var info2 = $('#info2E').val();
	var info3 =$('#info3E').val();
	var info4 =$('#info4E').val();
	var info5 =$('#info5E').val();
	var pu1 =$('#pu1E').val();
	if ( info1.length < 1  || info1.length >= 5000 ){
		$('#info1E').addClass('has-error');
		result = false;
	}if ( info2.length < 1  || info2.length >= 5000 ){
		$('#info2E').addClass('has-error');
		result = false;
	}if ( info3.length < 1  || info3.length >= 5000 ){
		$('#info3E').addClass('has-error');
		result = false;
	}if ( info4.length < 1  || info4.length >= 5000 ){
		$('#info4E').addClass('has-error');
		result = false;
	}if ( info5.length < 1  || info5.length >= 5000 ){
		$('#info5E').addClass('has-error');
		result = false;
	}if ( pu1.length < 1  || pu1.length >= 10 ){
		$('#pu1E').addClass('has-error');
		result = false;
	}
	notifications(result);
	return result;
}
function verifgestionAction(){
	var result = true;
	var gestionnaire = $('#gestionnaire').val();
	var action = $('#action').val();
	if ( gestionnaire.length < 1  || gestionnaire.length >= 50 ){
		$('#gestionnaire').addClass('has-error');
		result = false;
	}
	if ( action.length < 1  || action.length >= 50 ){
		$('#action').addClass('has-error');
		result = false;
	}
	notifications(result);
	return result;
}
function verifgestionPossed(){
	var result = true;
	var compte = $('#compte').val();
	var action = $('#action').val();
	var messageretrait = $('#messageretrait').val();


	if ( compte.length < 1  || compte.length >= 50 ){
		$('#compte').addClass('has-error');
		result = false;
	}
	if ( action.length < 1  || action.length >= 50 ){
		$('#action').addClass('has-error');
		result = false;
	}
	
	if ( messageretrait.length < 1  || messageretrait.length >= 3 ){
		$('#messageretraitEdit').addClass('has-error');
		result = false;
	}

	notifications(result);
	return result;
}


function verifgestionPossedEdit(){
	var result = true;
	var action = $('#action_edit').val();
	var messageretraitEdit = $('#messageretraitEdit').val();
	 
	if ( action.length < 1  || action.length >= 50 ){
		$('#action').addClass('has-error');
		result = false;
	}	
	if ( messageretraitEdit.length < 1  || messageretraitEdit.length >= 3 ){
		$('#messageretraitEdit').addClass('has-error');
		result = false;
	}

	notifications(result);
	return result;
}



function verificationCompte(){
	var table = $('#datatable-resposive').DataTable();
	var lengthRows = table.rows().count();
	var trouve = false;
	var result = true;
	var numcompte = $('#numcompte').val();
	var nom = $('#nom').val();
	var prenom = $('#prenom').val();
	var cin = $('#cin').val();
	var num_tel = $('#num_tel').val();
	var email_client = $('#email_client').val();
	var fb_client = $('#fb_client').val();
	var username = $('#username').val();
	var password = $('#password').val();
	var passwordverif = $('#passwordverif').val();
	var photo = $('#photo_client').val();

	if (  numcompte.length < 3 || numcompte.length >= 49 ){
		$('#numcompte').addClass('has-error');
		result = false;
	}else
	{
		var i = 0;
		while(i < lengthRows) {
			var numcompteExist = table.cell(i,1).data();
			if( numcompte == numcompteExist ){
				$('#numcompte').addClass('has-error');
				result = false; break;}
			i++;
		}
	}if ( nom.length < 3 || nom.length >= 20 ){
		$('#nom').addClass('has-error');
		result = false;
	}if ( prenom.length < 3 || prenom.length >= 20 ){
		$('#prenom').addClass('has-error');
		result = false;
	}if (  cin.length < 3 || cin.length >= 49 ){
		$('#cin').addClass('has-error');
		result = false;
	}else
	{
		var i = 0;
		while(i < lengthRows) {
			var cinExist = table.cell(i,4).data();
			if( cin == cinExist ){
				$('#cin').addClass('has-error');
				result = false; break;}
			i++;
		}
	}
	if ( num_tel.length < 7 || num_tel.length >= 15 ){
		$('#num_tel').addClass('has-error');
		result = false;
	}if ( email_client.length < 6 || email_client.length >= 50 ){
		$('#email_client').addClass('has-error');
		result = false;
	}else{
		var i = 0;
		while(i < lengthRows) {
			var email_clientExist = table.cell(i,6).data();
			if( email_client == email_clientExist ){
				$('#email_client').addClass('has-error');
				result = false; break;}
			i++;
		}
	}if ( fb_client.length < 2 || fb_client.length > 50 ){
		$('#fb_client').addClass('has-error');
		result = false;
	}if (  username.length < 3 || username.length >= 49 ){
		$('#username').addClass('has-error');
		result = false;
	}else
	{   var i = 0;
		while(i < lengthRows) {
			var usernameExist = table.cell(i,8).data();
			if( username == usernameExist ){
				$('#username').addClass('has-error');
				result = false; break;}
			i++;
		}
	}
	if ( password.length < 8 || password.length > 50){
		$('#password').addClass('has-error');
		result = false;
	}
	if ( password.length < 8 || passwordverif.length > 50 || password != passwordverif ){
		$('#passwordverif').addClass('has-error');
		result = false;
	}
	if (photo.length == 0){
		$('.label-file').addClass('has-error');
		$('#imgPhoto').addClass('has-error');
		result = false;
	}
	notifications(result);
	return result;
}

function verificationCompteEdit(){
	var table = $('#datatable-resposive').DataTable();
	var lengthRows = table.rows().count();
	var trouve = false;
	var result = true;
	var numcompte = $('#numcompteEdit').val();
	var nom = $('#nomEdit').val();
	var prenom = $('#prenomEdit').val();
	var cin = $('#cinEdit').val();
	var num_tel = $('#num_telEdit').val();
	var email_client = $('#email_clientEdit').val();
	var fb_client = $('#fb_clientEdit').val();
	var username = $('#usernameEdit').val();
	var ignoreUser = $('#ignoreUser').val();
	var ignoreEmail = $('#ignoreEmail').val();
	var ignoreCIN = $('#ignoreCIN').val();
	var ignorenumcompte = $('#ignorenumcompte').val();
	var ignorenumTel = $('#ignorenumTel').val();
	if (  numcompte.length < 3 || numcompte.length >= 49 ){
		$('#numcompteEdit').addClass('has-error');
		result = false;
	}else
	{
		var i = 0;
		while(i < lengthRows) {
			var numcompteExist = table.cell(i,1).data();
			if( numcompte == numcompteExist && numcompte != ignorenumcompte ){
				$('#numcompteEdit').addClass('has-error');
				result = false; break;}
			i++;
		}
	}if ( nom.length < 3 || nom.length >= 20 ){
		$('#nomEdit').addClass('has-error');
		result = false;
	}if ( prenom.length < 3 || prenom.length >= 20 ){
		$('#prenomEdit').addClass('has-error');
		result = false;
	}if (  cin.length < 3 || cin.length >= 49 ){
		$('#cinEdit').addClass('has-error');
		result = false;
	}else
	{
		var i = 0;
		while(i <= lengthRows) {
			var cinExist = table.cell(i,4).data();
			if( cin == cinExist && cin != ignoreCIN){
				$('#cinEdit').addClass('has-error');
				result = false; break;}
			i++;
		}
	}if (  num_tel.length < 7 || num_tel.length >= 15 ){
		$('#num_telEdit').addClass('has-error');
		result = false;
	}else
	{
		var i = 0;
		while(i <= lengthRows) {
			var num_telExist = table.cell(i,5).data();
			if( num_tel == num_telExist && num_tel != ignorenumTel){
				$('#num_telEdit').addClass('has-error');
				result = false; break;}
			i++;
		}
	}
	if ( email_client.length < 6 || email_client.length >= 50 ){
		$('#email_clientEdit').addClass('has-error');
		result = false;
	}else{
		var i = 0;
		while(i < lengthRows) {
			var email_clientExist = table.cell(i,6).data();
			if( email_client == email_clientExist && email_client != ignoreEmail ){
				$('#email_clientEdit').addClass('has-error');
				result = false; break;}
			i++;
		}
	}if ( fb_client.length < 2 || fb_client.length > 50 ){
		$('#fb_clientEdit').addClass('has-error');
		result = false;
	}if (  username.length < 3 || username.length >= 49 ){
		$('#usernameEdit').addClass('has-error');
		result = false;
	}else
	{   var i = 0;
		while(i < lengthRows) {
			var usernameExist = table.cell(i,8).data();
			if( username == usernameExist && username != ignoreUser ){
				$('#usernameEdit').addClass('has-error');
				result = false; break;}
			i++;
		}
	}
	notifications(result);
	return result;
}

function verificationUserEdit(){
	var table = $('#datatable-resposive').DataTable();
	var lengthRows = table.rows().count();
	var result = true;
	var username = $('#usernameEdit').val();
	var email = $('#emailEdit').val();
	var rolesEdit = $('#rolesEdit').val();
	var password = $('#passwordEdit').val();
	var passwordverif = $('#passwordverifEdit').val();
	var ignoreUser = $('#ignoreUser').val();
	var ignoreEmail = $('#ignoreEmail').val();
	if (  username.length < 3 || username.length >= 49 ){
		$('#usernameEdit').addClass('has-error');
		result = false;
	}else
	{   var i = 0;
		while(i < lengthRows) {
			var usernameExist = table.cell(i,0).data();
			if( username == usernameExist && username != ignoreUser ){
				$('#usernameEdit').addClass('has-error');
				result = false; break;}
			i++;
		}
	}
	if ( email.length < 6 || email.length >= 70 ){
		$('#emailEdit').addClass('has-error');
		result = false;
	}else{
		var i = 0;
		while(i < lengthRows) {
			var emailExist = table.cell(i,1).data();
			if( email == emailExist && email != ignoreEmail ){
				$('#emailEdit').addClass('has-error');
				result = false; break;}
			i++;
		}
	}if (password.length < 1 && passwordverif.length < 1  && password == passwordverif) {
	}else{
		if ( password.length < 8 || password.length > 50){
			$('#passwordEdit').addClass('has-error');
			result = false;
		}
		if ( password.length < 8 || passwordverif.length > 20 || password != passwordverif ){
			$('#passwordverifEdit').addClass('has-error');
			result = false;
		}}
	if ( rolesEdit.length < 1  || rolesEdit.length >= 20 ){
		$('#rolesEdit').addClass('has-error');
		result = false;
	}
	notifications(result);
	return result;
}

function verificationCateg(){
	var table = $('#datatable-responsive1').DataTable();
	var lengthRows = table.rows().count();
	var result = true;
	var type = $('#type').val();
	var i = 0;

	/*
	if (  type < 3 || username.length >= 49 ){
		$('#usernameEdit').addClass('has-error');
		result = false;
	}

	while(i < lengthRows) {
		if ( type == table.cell(i, 0).data()){
			result = false;
			$('#type').addClass('has-error');
		}
		i++;
	}
	*/
		
	var type_cat = $('#type').val();
	 
	if ( type_cat.length < 1  || type_cat.length >= 50 ){
		$('#action').addClass('has-error');
		result = false;
	}	

	notifications(result);
	return result;
}

function verificationCategEdit(){
	var table = $('#datatable-responsive1').DataTable();
	var lengthRows = table.rows().count();
	var result = true;
	var type = $('#typeEdit').val();
	var ignorecat = $('#ignorecat').val();
	var i = 0;
	
	/*
	while(i < lengthRows) {
		var typeExist = table.cell(i,0).data();
		if( type == typeExist && type != ignorecat ){
			$('#typeEdit').addClass('has-error');
			result = false; break;}
		i++;
	}

	*/
	
	var type_cat = $('#typeEdit').val();
	 
	if ( type_cat.length < 1  || type_cat.length >= 50 ){
		$('#action').addClass('has-error');
		result = false;
	}	

	notifications(result);
	return result;
 
}