//This nice code is taken from http://romka.eu/blog/dinamicheskoe-dobavlenie-elementov-k-forme

jQuery.noConflict();

function addperms() {
	var id = document.getElementById("hidden-1").value;
  id++;
  jQuery("div[id=a4]").append('<div id="wpelf-perms-div-' + id + '"><input type="text" name="wpelf_perms-' + id + '" id="wpelf_perms-' + id + '" class="regular-text code"> <label for="wpelf-perms-read-' + id + '"><input name="wpelf_perms_read-' + id + '" type="checkbox" id="wpelf-perms-read-' + id + '" value="true" /> read</label> <label for="wpelf-perms-write-' + id + '"><input name="wpelf_perms_write' + id + '" type="checkbox" id="wpelf-perms-write-' + id + '" value="true" /> write</label> <label for="wpelf-perms-rm-' + id + '"><input name="wpelf_perms_remove' + id + '" type="checkbox" id="wpelf-perms-rm-' + id + '" value="true" /> remove</label> <input type="button" class="button" value="Remove" onclick="removeperms(\'' + id + '\')"></div>');
  document.getElementById("hidden-1").value = id;
}

function removeperms(id) {
	jQuery("#wpelf-perms-div-" + id).remove();
}

function addcreate() {
	var id = document.getElementById("hidden-2").value;
  id++;
  jQuery("div[id=b5]").append('<div id="wpelf-create-div-' + id + '"><input type="text" id="wpelf_create_rule-' + id + '" name="wpelf_create_rule-' + id + '" class="regular-text code"> <input name="wpelf-create-cmd-' + id + '" type="text" id="wpelf-create-cmd-' + id + '" class="small-text code" /> <input name="wpelf-create-argc-' + id + '" type="text" id="wpelf-create-argc-' + id + '" class="small-text code" /> <input name="wpelf-create-ext-' + id + '" type="text" id="wpelf-create-ext-' + id + '" class="small-text code" /> <input type="button" class="button" value="Remove" onclick="removecreate(\'' + id + '\')"></div>');
  document.getElementById("hidden-2").value = id;
}

function removecreate(id) {
	jQuery("#wpelf-create-div-" + id).remove();
}

function addextract() {
	var id = document.getElementById("hidden-3").value;
  id++;
  jQuery("div[id=c7]").append('<div id="wpelf-extract-div-' + id + '"><input type="text" id="wpelf_extract_rule-' + id + '" name="wpelf_extract_rule-' + id + '" class="regular-text code"> <input name="wpelf-extract-cmd-' + id + '" type="text" id="wpelf-extract-cmd-' + id + '" class="small-text code" /> <input name="wpelf-extract-argc-' + id + '" type="text" id="wpelf-extract-argc-' + id + '" class="small-text code" /> <input name="wpelf-extract-ext-' + id + '" type="text" id="wpelf-extract-ext-' + id + '" class="small-text code" /> <input type="button" class="button" value="Remove" onclick="removeextract(\'' + id + '\')"></div>');
  document.getElementById("hidden-3").value = id;
}

function removeextract(id) {
	jQuery("#wpelf-extract-div-" + id).remove();
}