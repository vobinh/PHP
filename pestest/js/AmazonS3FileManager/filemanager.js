/**
 * Amazon S3 File Manager for TinyMCE & CKEditor
 * 
 * @copyright 
 *  Copyright 2010, Aurigma 
 *  Dual licensed under the MIT and GPL licenses.
 *    http://www.opensource.org/licenses/mit-license.php
 *    http://www.gnu.org/licenses/gpl.html
 * 
 * @copyright
 * Includes code from Ajex.FileManager 
 * Copyright (C) 2009 Demphest Gorphek http://demphest.ru/ajex-filemanager
 * Dual licensed under the MIT and GPL licenses. 
 *  http://www.opensource.org/licenses/mit-license.php
 *  http://www.gnu.org/licenses/gpl.html
 * 
 * @fileOverview
 * This file is part of Amazon S3 File Manager for TinyMCE & CKEditor.
 */

var AmazonS3FileManager = {
	init: function(params) {
		//console.log(params.main_url);
		//console.log('<?php echo url::base()?>');
		if ('undefined' == typeof(params)) params = {};

		this.path = params.path || function() {
			var s = document.getElementsByTagName('script');
			for (var i=-1; ++i<s.length;) {
				if (s[i].getAttribute('src') && -1 != (src = s[i].getAttribute('src')).indexOf('/filemanager.js')) {
					return src.substring(0, src.lastIndexOf('/'));
				}
			}
			alert('Undefined variable "path" in AmazonS3FileManager.init({path:"/path/to/AmazonS3FileManager/"});');
			return null;
		}();
		if ('/' == this.path.substring(this.path.length-1)) {
			this.path = this.path.substring(0, this.path.length-1);
		}

		this.returnTo = params.returnTo || 'ckeditor';
		this.connector = params.connector || 'php';

		this.width = params.width || 1000;
		this.height = params.height || 660;
		this.skin = 'dark';
		this.lang = 'en';

		if ('ckeditor' == this.returnTo) {
			if ('undefined' != typeof(params.editor)) {
				params.editor.config['filebrowserWindowWidth']	= this.width;
				params.editor.config['filebrowserWindowHeight']	= this.height;
				params.editor.config['filebrowserBrowseUrl']	= params.main_url+'admin_questionnaires/AWS_Manager?connector=' + this.connector + '&lang=' + this.lang + '&returnTo=' + this.returnTo + '&skin=' + this.skin;
				//params.editor.config['filebrowserBrowseUrl']	= this.path + '/index.html?connector=' + this.connector + '&lang=' + this.lang + '&returnTo=' + this.returnTo + '&skin=' + this.skin;
				//params.editor.config['filebrowserUploadUrl']	= this.path + '/ajax/' + this.connector + '/ajax.' + this.connector + '?mode=QuickUpload';
			} else {
				alert('You need to pass the object in the variable "editor"');
			}

		} else if('tinymce' == this.returnTo) {

		} else {
			this.url = this.path + '/index.html?connector=' + this.connector + '&lang=' + this.lang + '&skin=' + this.skin;
			this.args = 'width=' + this.width +',height=' + this.height + ',resizable=1,menubar=0,scrollbars=1,location=1,left=0,top=0,screenx=,screeny=';
		}

		return;
	},

	open: function(params, url, type, win) {
		console.log(params);
		if ('undefined' != typeof(params.returnTo)) {
			returnTo = params.returnTo;
		} else {
			returnTo = this.returnTo;
		}

		switch(returnTo) {
			case 'ckeditor':
				break;

			case 'tinymce':
			    tinyMCE.activeEditor.windowManager.open({
			        url: this.path + '/index.html?connector=' + this.connector + '&returnTo=' + this.returnTo + '&lang=' + this.lang + '&skin=' + this.skin,
			        width: this.width,
			        height: this.height,
			        inline: 'yes',
			        close_previous: 'no'
			    }, {
			        window: win,
			        input: params
			    });
				break;

			default:
				var win = window.open(this.url + '&returnTo=' + returnTo, 'AmazonS3FileManager', this.args);
				win.focus();
				break;
		}

		return;
	}

}
