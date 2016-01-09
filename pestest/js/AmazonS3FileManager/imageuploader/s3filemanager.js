/**
 * Amazon S3 File Manager for TinyMCE & CKEditor
 * 
 * @copyright 
 *  Copyright 2010, Aurigma 
 *  Dual licensed under the MIT and GPL licenses. 
 *    http://www.opensource.org/licenses/mit-license.php
 *    http://www.gnu.org/licenses/gpl.html
 * 
 * 
 * This file is part of Amazon S3 File Manager for TinyMCE & CKEditor.
 */

var uploadCount = 0; // for tracking what files should we sent (sourse or
// thumbnail)
var totalFilesUploaded = 0; // Count the uploaded files to make the custom
// progress
var cancelled = false; // flag the upload cancelled

function send() {
	totalFilesUploaded = 0;
	uploadCount = 0;
	cancelled = false;
	var u = getImageUploader("ImageUploader1");
	// send files
	u.Send();
}

function sendItem() {
	if (!cancelled) {
		var u = getImageUploader("ImageUploader1");
		if (uploadCount >= 2) {
			uploadCount = 0;
			try {
				// throw error for activex
				u.setPaneItemChecked(1, false);
			} catch (e) {
				// works for activex
				u.PaneItemChecked(1) = false;
			}
		}
		if (u.getUploadFileCount() > 0) {
			u.Send();
		} else {
			afterUpload(); // all files has been uploaded. show complete
			// message
		}
	}
}

function beforeUpload() {
	var u = getImageUploader("ImageUploader1");

	as3.setBucket($cfg.as3.bucket);
	as3.setAWSAccessKeyId($cfg.as3.accessKey);

	as3.getSourceFile().setAcl($cfg.as3.acl);
	as3.getSourceFile().setPolicy($cfg.as3.policy);
	as3.getSourceFile().setSignature($cfg.as3.signature);

	as3.getThumbnail1().setAcl($cfg.as3.acl);
	as3.getThumbnail1().setPolicy($cfg.as3.policy);
	as3.getThumbnail1().setSignature($cfg.as3.signature);

	var node = $('#dirsList').dynatree('getActiveNode');
	if (!node) {
		return false;
	}

	// Get original file name
	var fileName = u.getUploadFileName(1);

	var folder = decodeURIComponent(node.data.key);
	var key = uploadCount == 0 ? $cfg.as3.root : $cfg.as3.thumb;
	if (folder) {
		key += '/' + folder + '/' + fileName;
	} else {
		key += '/' + fileName;
	}
	as3.getSourceFile().setKey(key);
	as3.getThumbnail1().setKey(key + '.jpg');

	switch (uploadCount) {
		case 0 : // send source file
			u.setUploadThumbnail1FitMode('None');
			u.setUploadSourceFile(true);
			break;
		case 1 : // send first thumbnail
			u.setUploadThumbnail1FitMode('Fit');
			u.setUploadSourceFile(false);
			break;
	}
}

function packageComplete(packageIndex, responsePage) {
	uploadCount++;
	totalFilesUploaded++;

	setTimeout(sendItem, 100);

	return false;
}

function afterUpload(html) {
	// Update items in current folder
	var node = $('#dirsList').dynatree('getActiveNode');
	if (node) {
		$cfg.dir = decodeURIComponent(node.data.key);
		viewsUpdate(node.data.key);
	}

	// reset upload counter
	uploadCount = 0;
	// reset uploaded file counter
	totalFilesUploaded = 0;

	// no more uploading, show complete message
	alert("Upload complete!");
}

function initProgress() {
	document.getElementById('loading').style.display = 'block';
}

function createUploader() {

	var iu = new ImageUploaderWriter('ImageUploader1', '100%', '160px');

	IUCommon.debugLevel = 0;

	// For ActiveX control full path to CAB file (including file name) should be
	// specified.
	iu.activeXControlCodeBase = "imageuploader/ImageUploader6.cab";
	iu.activeXControlVersion = "6,5,6,0";

	// For Java applet only path to directory with JAR files should be specified
	// (without file name).
	iu.javaAppletJarFileName = "ImageUploader6.jar";
	iu.javaAppletCodeBase = "imageuploader/";
	iu.javaAppletCached = true;
	iu.javaAppletVersion = "6.5.6.0";

	iu.addParam("LicenseKey",
			"71060-47A8C-00000-0967B-F5E09;72060-47A8C-00000-0B109-36060");

	iu.showNonemptyResponse = "off";

	// Configure thumbnail settings.
	iu.addParam("UploadThumbnail1Width", "100");
	iu.addParam("UploadThumbnail1Height", "100");
	iu.addParam("UploadThumbnail1JpegQuality", "80");

	// Configure appearance.
	iu.addParam("PaneLayout", "OnePane");
	iu.addParam("ShowButtons", "False");;
	iu.addParam("AllowRotate", "false");
	iu.addParam("TreePaneBorderStyle", "None");
	iu.addParam("UploadPaneBorderStyle", "None");
	iu.addParam("FolderPaneBorderStyle", "None");
	iu.addParam("ShowDescriptions", "false");
	iu.addParam("PaneBackgroundColor", "#2E2E2E");
	iu.addParam("UploadPaneBackgroundColor", "#2E2E2E");
	iu.addParam("UseSystemColors", "false");
	iu.addParam("PreviewThumbnailActiveSelectionColor", "#288DB1");
	iu.addParam("PreviewThumbnailInactiveSelectionColor", "#288DB1");
	iu.addParam("DisplayNameActiveSelectedTextColor", "#F5FFF6");
	iu.addParam("DisplayNameActiveUnselectedTextColor", "#F5FFF6");
	iu.addParam("DisplayNameInactiveSelectedTextColor", "#F5FFF6");
	iu.addParam("DisplayNameInactiveUnselectedTextColor", "#F5FFF6");
	iu.addParam("PreviewThumbnailBorderHoverColor", "#AAAAAA");
	iu.addParam("PreviewThumbnailBorderColor", "#5A5A5A");

	// Don't show progress bar and any messages during upload.
	iu.addParam("SilentMode", "true");

	// Disable auto remove uploaded files from upload list
	iu.addParam("UncheckUploadedFiles", "false");

	// Add event handlers
	iu.addEventListener("BeforeUpload", "beforeUpload");
	iu.addEventListener("PackageComplete", "packageComplete");

	// Create Amazon S3 extender
	window.as3 = new AmazonS3Extender(iu);

	// Tell Image Uploader writer object to generate all necessary HTML code to
	// embed
	// Image Uploader to the page.
	iu.writeHtml();
}