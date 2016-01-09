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

var $cfg = {
	display : {
		fileName : true,
		fileDate : false,
		fileSize : false
	},
	view : {
		list : false,
		thumb : true
	},
	menu : {
		file : {},
		folder : {}
	},
	cutKey : 15,
	dir : '',
	file : '',
	thumb : '',
	skin : 'dark',
	lang : 'en',
	type : 'file',
	sort : 'name',
	returnTo : 'ckeditor',
	tmp : []
};
if ('' != (isSkin = parseUrl('skin'))) {
	$cfg.skin = isSkin;
}
if ('' != (isReturn = parseUrl('returnTo'))) {
	$cfg.returnTo = isReturn;
}
if ('' != (isLang = parseUrl('langCode'))) {
	$cfg.lang = isLang;
}
if ('' != (isLang = parseUrl('lang'))) {
	$cfg.lang = isLang;
}

var $ajaxConnector = 'ajax/php/ajax.php';
if ('' != (isConnector = parseUrl('connector'))) {
	switch (isConnector) {
		case '###' :
			break;
		case 'php' :
		default :
			// $ajaxConnector = 'ajax/php/ajax.php';
	}
}

$('head').prepend('<script type="text/javascript" src="lang/' + $cfg.lang
		+ '.js"></script>');
$('head').append('<link type="text/css" href="skin/' + $cfg.skin + '/'
		+ $cfg.skin + '.css" rel="stylesheet" />');

var statusDiv = {};
var dialogDiv = {};

var Action = {
	createFolder : function() {
		$cfg.tmp['mode'] = 'createFolder';
		$cfg.tmp['oldname'] = $cfg.dir;
		$cfg.tmp['key'] = $cfg.dir;

		dialogSet(
				$lang.enterNameCreateFolder,
				'<b>'
						+ $lang.location
						+ '</b> ['
						+ $cfg.url
						+ $cfg.dir
						+ '/]<br /><input type="text" id="newName" value="" class="t" /><br />'
						+ $lang.allowRegSymbol);
		return;
	},
	deleteFolder : function() {
		if ('root' == $cfg.dir || '' == $cfg.dir) {
			return false;
		}

		$('#dirsList').dynatree('disable');
		$.post($ajaxConnector + '?mode=deleteFolder', {
					dir : $cfg.dir,
					lang : $cfg.lang
				}, function(reply) {
					if (reply.isDelete) {
						var key = $cfg.dir.substring(0, $cfg.dir
										.lastIndexOf('/'));
						var tree = $('#dirsList').dynatree('getTree');
						var node = tree.getNodeByKey(encodeURIComponent(key));
						if (node) {
							node.reload(true);
						} else {
							var disable = true;
							reloadTree();
						}

						$('>div.l', statusDiv).html('<div class="warning"><b>'
								+ $lang.successDeleteFolder + '</b></div>');
					} else {
						$('>div.l', statusDiv).html('<div class="warning"><b>'
								+ $lang.failedDeleteFolder + '</b></div>');
					}
					!disable ? $('#dirsList').dynatree('enable') : null;
				}, 'json');
		return;
	},
	deleteFiles : function() {
		var files = [];
		$('#fileThumb input[name="file\\[\\]"]:checked').each(function() {
					files.push(this.value);
				});
		if (!files.length) {
			return;
		}

		$.post($ajaxConnector + '?mode=deleteFiles', {
					dir : $cfg.dir,
					files : files.join('::'),
					lang : $cfg.lang
				}, function(reply) {
					appendFiles(reply);
				}, 'json');
	},
	deleteFile : function() {
		if ('' == $cfg.file)
			return false;
		$.post($ajaxConnector + '?mode=deleteFiles', {
					dir : $cfg.dir,
					files : $cfg.file,
					lang : $cfg.lang
				}, function(reply) {
					appendFiles(reply);
				}, 'json');
	},
	setThumb : function() {
		if ('' == $cfg.file)
			return false;
		_setReturnData($cfg.thumb);
	},
	setFile : function() {
		if ('' == $cfg.file)
			return false;
		_setReturnData($cfg.url + $cfg.dir + '/' + $cfg.file);
	}
};

$(document).ready(function() {

	$('#loading').bind('ajaxSend', function() {
				$(this).show();
			}).bind('ajaxComplete', function() {
				$(this).hide();
			});

	$.post($ajaxConnector + '?mode=cfg', {
				lang : $cfg.lang
			}, function(reply) {
				for (var i in reply.config) {
					$cfg[i] = reply.config[i];
				}

				$('#dirsList').dynatree({
							title : 'upload',
							rootVisible : true,
							persist : false,
							clickFolderMode : 1,
							fx : {
								height : "toggle",
								duration : 200
							},
							children : $cfg.children,
							onActivate : function(dtnode) {
								$cfg.file = '';
								$cfg.dir = decodeURIComponent(dtnode.data.key);
								viewsUpdate(dtnode.data.key);
								return;
							},
							onLazyRead : function(dtnode) {
								$.post($ajaxConnector + '?mode=getDirs', {
											dir : dtnode.data.key,
											lang : $cfg.lang
										}, function(reply) {
											dtnode.addChild(reply.dirs);
											dtnode
													.setLazyNodeStatus(DTNodeStatus_Ok);
										}, 'json');

								return false;
							}
						});

			}, 'json');

	$('#dirPanel > ul.Toolbar a.createfolder').click(function() {
				var node = $('#dirsList').dynatree('getActiveNode');
				if (!node)
					return;
				var root = $('#dirsList').dynatree('getRoot');
				var key = node == root ? '' : node.data.key;
				$cfg.dir = decodeURIComponent(key);
				Action.createFolder();
			});

	$('#dirPanel > ul.Toolbar a.deletefolder').click(function() {
				Action.deleteFolder();
			});

	$('#listPanel > ul.Toolbar a.selectfile').click(function() {
				Action.setFile();
			});
	$('#listPanel > ul.Toolbar a.deletefile').click(function() {
				Action.deleteFile();
			});
	$('#listPanel > ul.Toolbar a.checkall').click(function() {
				$('#fileThumb input[name="file\\[\\]"]').attr('checked',
						'checked');
			});
	$('#listPanel > ul.Toolbar a.uncheckall').click(function() {
				$('#fileThumb input[name="file\\[\\]"]').removeAttr('checked');
			});
	$('#listPanel > ul.Toolbar a.deletechecked').click(function() {
				Action.deleteFiles();
			});

	statusDiv = $('#status');
	dialogDiv = $('#dialog');

	for (var i in $lang) {
		$('span[lang="' + i + '"]').text($lang[i]);
	}

	$(dialogDiv).dialog({
		bgiframe : true,
		resizable : false,
		width : 400,
		height : 180,
		modal : true,
		autoOpen : false,
		overlay : {
			backgroundColor : '#000',
			opacity : 0.5
		},
		buttons : {
			'Cancel' : function() {
				$(this).dialog('close');
			},
			' OK ' : function() {
				var newname = $('#newName').val();
				if (!/^[a-z0-9-_#~\$%()\[\]&=]+/i.test(newname)) {
					return false;
				}
				$(this).dialog('close');
				$('#dialog input').attr('disabled', 'disabled');

				$.post($ajaxConnector + '?mode=' + $cfg.tmp['mode'], {
							dir : $cfg.dir,
							lang : $cfg.lang,
							oldname : $cfg.tmp['oldname'],
							newname : newname
						}, function(reply) {
							if (reply.isSuccess
									&& ('createFolder' == $cfg.tmp['mode'] || 'renameFolder' == $cfg.tmp['mode'])) {
								if ('exist' == reply.isSuccess) {
									$('>div.l', statusDiv)
											.html('<div class="warning"><b>'
													+ $lang.folderExist
													+ '</b></div>');
									return;
								}
								var tree = $('#dirsList').dynatree('getTree');
								var node = tree
										.getNodeByKey(encodeURIComponent($cfg.tmp['key']));
								if (node) {
									node.reload(true);
								} else {
									reloadTree();
								}
							} else {
								appendFiles(reply);
							}
						}, 'json');

				return;
			}
		}
	});

	if ($('#author a').length)
		$('#author a').attr('target', '_blank');
	else
		$('#files').html(''); // :/
});

function reloadTree() {
	// reload tree
	var tree = $('#dirsList').dynatree('getTree');
	$('#dirsList').dynatree('disable');
	$.post($ajaxConnector + '?mode=getDirs', {
				dir : '',
				lang : $cfg.lang
			}, function(reply) {
				$cfg.children = tree.options.children = reply.dirs;
				tree.reload();
				$('#dirsList').dynatree('enable');
				$cfg.dir = '';
			}, 'json');
}

function formatSize(size) {
	size = parseInt(size, 10);
	if (isNaN(size)) {
		return '';
	}
	if (size >= 1024 * 1024) { // to megabytes
	 size = Math.round(size / (1024 * 1024));
	 size += ' MB';
	} else if (size >= 1024) { // to kilobytes
		size = Math.round(size / (1024));
    size += ' KB';
	} else { // bytes
		size += ' B'
	}
	return size;
}

function dialogSet(title, html) {
	$('div.ui-dialog span.ui-dialog-title').html(title);
	$(dialogDiv).html(html);
	$(dialogDiv).dialog('open');
	$('#newName').focus();
	return;
}

function _setReturnData(input, data) {
	switch ($cfg.returnTo) {
		case 'ckeditor' :
			window.top.opener['CKEDITOR'].tools.callFunction(
					parseUrl('CKEditorFuncNum'), input, data);
			window.top.close();
			window.top.opener.focus();
			break;

		case 'tinymce' :
			var win = window.dialogArguments || opener || parent || top;
			tinyMCE = win.tinyMCE;
			var params = tinyMCE.activeEditor.windowManager.params;
			params.window.document.getElementById(params.input).value = input;
			try {
				params.window.ImageDialog.showPreviewImage(input);
			} catch (e) {
			}
			window.close();
			break;

		default :
			try {
				if ('$' == $cfg.returnTo.substr(0, 1)) {
					var objInput = $cfg.returnTo.substr(1);
					window.top.opener.document.getElementById(objInput).value = input;
				} else {
					window.top.opener[$cfg.returnTo](input);
				}
				window.close();
			} catch (e) {
				alert('Function is not available or does not exist: '
						+ $cfg.returnTo + "\r" + e.message);
			}
	}

	return true;
}

function viewsUpdate(dir) {
	if ('root' == dir)
		return;

	$.post($ajaxConnector + '?mode=getFiles', {
				dir : dir,
				lang : $cfg.lang,
				sort : $cfg.sort
			}, function(reply) {
				appendFiles(reply);
			}, 'json');

	return;
}

function appendFiles(reply) {
	$('>div.l', statusDiv).html('<div>' + $cfg.url + $cfg.dir
			+ '/</div><div><b>' + reply.files.length + '</b> ' + $lang.fileOf
			+ '</div>');

	var files = reply.files;
	var thumb = '', w_h = '', attr = '';

	for (var i in files) {
		attr = 'file="' + ($cfg.url + $cfg.dir + '/' + files[i].name)
				+ '" thumb="'
				+ (files[i].thumb ? $cfg.thumbUrl + files[i].thumb : '') + '"';

		thumb += '<div class="item" ' + attr + '>';
		thumb += '<div class="image ext_' + files[i].ext + '">';
		if (files[i].thumb) {
			// show thumbnail
			// add onload event to hide file icon
			thumb += '<img onload="this.parentNode.style.backgroundImage=\'none\';" src="'
					+ $cfg.thumbUrl + files[i].thumb + '" alt="" />';
		}
		thumb += '</div>'; // image

		thumb += '<input class="check" type="checkbox" name="file[]" value="'
				+ files[i].name + '" />';

		thumb += '<div class="caption">';
		thumb += files[i].name;
		thumb += '</div>'; // caption
		
		thumb += '<div class="size">';
    thumb += formatSize(files[i].size);
    thumb += '</div>'; // size
    
    thumb += '</div>'; // item
	}

	$('#fileThumb').html(thumb);
	$('#fileThumb > div.item').each(function() {

		var div = $(this);
		div.click(function() {
			$('#fileThumb > div.item').removeClass('selected');
			$cfg.file = $('.caption', div).text();
			$cfg.thumb = $(div).attr('thumb');
			$('>div.l', statusDiv).html('<div class="cutName"><a href="'
					+ $cfg.url + $cfg.dir + '/' + $cfg.file
					+ '" target="_urlFile">' + $cfg.url + $cfg.dir + '/'
					+ $cfg.file + '</a></div><div>' + $lang.fileSize + ': '
					+ $('.size', div).text() + '</div>');
			div.addClass('selected');

		}).dblclick(function() {
			_setReturnData($cfg.url + $cfg.dir + '/'
					+ $('.caption', div).text());
		});

	});

	$('#fileList input[name="file\\[\\]"]').click(function() {
		$(this).attr('checked')
				? $('#fileThumb input[value="' + $(this).attr('value') + '"]')
						.attr('checked', 'checked')
				: $('#fileThumb input[value="' + $(this).attr('value') + '"]')
						.removeAttr('checked');
	});
	$('#fileThumb input[name="file\\[\\]"]').click(function() {
		$(this).attr('checked')
				? $('#fileList input[value="' + $(this).attr('value') + '"]')
						.attr('checked', 'checked')
				: $('#fileList input[value="' + $(this).attr('value') + '"]')
						.removeAttr('checked');
	});
	return;
}

/*
 * ----- misc
 * 
 */

function parseUrl(name) {
	name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
	var regexS = "[\\?&]" + name + "=([^&#]*)";
	var regex = new RegExp(regexS);
	var results = regex.exec(window.location.href);
	if (null == results) {
		return '';
	}
	return results[1];
}
