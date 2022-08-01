function translit(text){
	var s_text = text.toString().toLowerCase();
	var transl=new Array();
	transl['а']='a';		transl['б']='b';
	transl['в']='v';		transl['г']='g';
	transl['д']='d';		transl['е']='e';
	transl['ё']='yo';		transl['ж']='zh';
	transl['з']='z';		transl['и']='i';
	transl['й']='j';		transl['к']='k';
	transl['л']='l';		transl['м']='m';
	transl['н']='n';		transl['о']='o';
	transl['п']='p';		transl['р']='r';
	transl['с']='s';		transl['т']='t';
	transl['у']='u';		transl['ф']='f';
	transl['х']='x';		transl['ц']='c';
	transl['ч']='ch';		transl['ш']='sh';
	transl['щ']='sch';		transl['ы']='y';
	transl['э']='e';		transl['ю']='u';
	transl['я']='ya';		transl['ь']='';
	transl['ъ']='';

	transl['a']='a';		transl['n']='n';
	transl['b']='b';		transl['o']='o';
	transl['c']='c';		transl['p']='p';
	transl['d']='d';		transl['q']='q';
	transl['e']='e';		transl['r']='r';
	transl['f']='f';		transl['s']='s';
	transl['g']='g';		transl['t']='t';
	transl['h']='h';		transl['u']='u';
	transl['i']='i';		transl['v']='v';
	transl['j']='j';		transl['w']='w';
	transl['k']='k';		transl['x']='x';
	transl['l']='l';		transl['y']='y';
	transl['m']='m';		transl['z']='z';

	transl['1']='1';		transl['5']='5';
	transl['2']='2';		transl['6']='6';
	transl['3']='3';		transl['7']='7';
	transl['4']='4';		transl['8']='8';
	transl['9']='9';		transl['0']='0';


	var result='';
	for(i = 0; i < s_text.length; i++) {
		if (transl[s_text[i]] != undefined) {
			result += transl[s_text[i]];
		} else {
			result+="-";
		}
	}
	return result;
}
function responsive_filemanager_callback(field_id){
	
	$('#imageModal').modal('hide');
	$('#preview_'+field_id+' img').attr('src',$('#'+field_id).val());
}	

function tinymce_init_editors() {
	tinymce.init({
		selector: "textarea.editor",
		height:400,
		menubar:false,
		statusbar:false,
		relative_urls:false,
		plugins: [
		    "advlist autolink lists link image charmap print preview anchor",
			"searchreplace visualblocks code fullscreen",
			"insertdatetime media table contextmenu paste code",
			"responsivefilemanager"
		],
		toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image responsivefilemanager | code",
		   
		external_filemanager_path:"/filemanager/",
		filemanager_title:"Файловый менеджер" ,
		external_plugins: { "filemanager" : "/filemanager/plugin.min.js"}
	});
	tinymce.init({
		selector: "textarea.editor-mini",
		menubar:	false, 
		statusbar: 	false,
		relative_urls:false,
		//content_css : "/css/common/content.css",
	    plugins: [
	        "image code",
	    ],
	    toolbar: "bold italic | code"
	});
}

$(function() {
	tinymce_init_editors();
	var _sname = 'input[data-column=name]';
	var _ssurn = 'input[data-column=surname]';
	var _salia = 'input[data-column=alias]';
	
	var hasName = 		$(_sname).length > 0;
	var hasSurname = 	$(_ssurn).length > 0;
	var hasAlias = 		$(_salia).length > 0;
	
	if(hasAlias) {
		var _kb = function() {
			var _value = $(_sname).val();
			if(hasSurname) _value += "-"+$(_ssurn).val();
			var _tvalue = translit(_value);
			$(_salia).attr('value',_tvalue);
		};
		//вешаем на name и surname формирование alias
		if(hasName) {
			$(_sname).keyup	(_kb);
			$(_sname).blur	(_kb);
		}
		if(hasSurname) {
			$(_ssurn).keyup	(_kb);
			$(_ssurn).blur	(_kb);
		}
	}
	
});