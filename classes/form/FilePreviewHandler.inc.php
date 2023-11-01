<?php

/**
 * @file plugins/generic/filePreview/classes/form/FilePreviewHandler.inc.php
 *
 * Copyright (c) 2023-2023 Universidad Nacional de Lanus.
 * Distributed under the GNU GPL v2 or later. For full terms see the LICENSE file.
 *
 * @brief Handles controller requests for FilePreview plugin.
 */

import('classes.handler.Handler');

class FilePreviewHandler extends Handler {

	/**
	 * @copydoc GridHandler::initialize()
	 */
	function initialize($request, $args = null) {
		parent::initialize($request, $args);
		AppLocale::requireComponents(
			LOCALE_COMPONENT_PKP_GRID
		);
	}

	/**
	 * Handle filePreview action
	 * @param $args array Arguments array.
	 * @param $request PKPRequest Request object.
	 */
	function filePreview($args, $request) {

		$templateMgr = TemplateManager::getManager($request);
		$context = $request->getContext();
		$plugin = PluginRegistry::getPlugin('generic', 'filepreviewplugin');

		$contextId = ($context == null) ? 0 : $context->getId();

		$fileUrl = $args['fileUrl'];
		$fileUrl = str_replace("$", "%24", $fileUrl);
		$templateMgr->assign(array(
			'pluginUrl' => $request->getBaseUrl() . '/' . $plugin->getPluginPath(),
			'fileUrl' => $fileUrl,
		));

		import('plugins.generic.filePreview.classes.form.FilePreviewForm');
		$form = new FilePreviewForm($plugin, $contextId, $args);
		$form->initData();
		return new JSONMessage(true, $form->fetch($request));
	}
}