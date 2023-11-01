<?php

/**
 * @file plugins/generic/filePreview/FilePreviewPlugin.inc.php
 *
 * Copyright (c) 2023-2023 Universidad Nacional de Lanus.
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class FilePreviewPlugin
 * @ingroup plugins_generic_filepreview
 *
 * @brief File preview plugin
 *
 */

import('lib.pkp.classes.plugins.GenericPlugin');

/**
 * Class FilePreviewPlugin
 */
class FilePreviewPlugin extends GenericPlugin {
	/**
	 * @copydoc Plugin::getDisplayName()
	 */
	function getDisplayName() {

		return __('plugins.generic.filePreview.displayName');
	}

	/**
	 * @copydoc Plugin::getDescription()
	 */
	function getDescription() {

		return __('plugins.generic.filePreview.description');
	}

	/**
	 * @copydoc Plugin::register()
	 */
	function register($category, $path, $mainContextId = null) {

		if (parent::register($category, $path, $mainContextId)) {
			if ($this->getEnabled()) {
				// Register callbacks.
				HookRegistry::register('TemplateManager::fetch', array($this, 'templateFetchCallback'));
				HookRegistry::register('LoadComponentHandler', array($this, 'callBackLoadHandler'));
				$this->_registerTemplateResource();
			}
			return true;
		}
		return false;
	}


	/**
	 * Adds additional links to submission files grid row
	 * @param $hookName string The name of the invoked hook
	 * @param $params array Hook parameters
	 */
	public function templateFetchCallback($hookName, $params) {

		$request = $this->getRequest();
		$router = $request->getRouter();
		$dispatcher = $router->getDispatcher();

		$templateMgr = $params[0];
		$resourceName = $params[1];
		if ($resourceName == 'controllers/grid/gridRow.tpl') {
			$row = $templateMgr->getTemplateVars('row');
			$data = $row->getData();
			if (is_array($data) && (isset($data['submissionFile']))) {
				$submissionFile = $data['submissionFile'];
				$fileExtension = strtolower($submissionFile->getData('mimetype'));
				$stageId = (int)$request->getUserVar('stageId');

				if (strtolower($fileExtension) == 'application/pdf') {
					import('lib.pkp.classes.linkAction.request.OpenWindowAction');
					$this->_previewAction($row, $dispatcher, $request, $submissionFile, $stageId);
					$this->modalAction($row, $dispatcher, $request, $submissionFile, $stageId);
				}
				elseif (strtolower($fileExtension) == 'application/msword') {
					// CONVERTIR ARCHIVO A PDF Y MOSTRARLO
				}
				elseif (strtolower($fileExtension) == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
					// CONVERTIR ARCHIVO A PDF Y MOSTRARLO
				}
			}
		}
	}

	/**
	 * Add preview with file Preview action to files grid
	 * @param $row SubmissionFilesGridRow
	 * @param Dispatcher $dispatcher
	 * @param PKPRequest $request
	 * @param $submissionFile SubmissionFile
	 * @param int $stageId
	 */
	private function modalAction($row, Dispatcher $dispatcher, PKPRequest $request, $submissionFile, int $stageId): void {
		$router = $request->getRouter();
		import('lib.pkp.classes.linkAction.request.AjaxModal');

		$actionArgs['fileUrl'] = $dispatcher->url($request, ROUTE_COMPONENT, null, 'api.file.FileApiHandler', 'downloadFile', null,
			array(
				'submissionFileId' => $submissionFile->getData('id'),
				'submissionId' => $submissionFile->getData('submissionId'),
				'stageId' => $stageId
			)
			);

		$row->addAction(
			new LinkAction(
				'Preview',
				new AjaxModal($router->url($request, null, null, 'filePreview', null, $actionArgs),
					$this->getDisplayName()),
				__('plugins.generic.filePreview.preview'),
				null
			),
		);
	}

	/**
	 * Add preview with file Preview action to files grid
	 * @param $row SubmissionFilesGridRow
	 * @param Dispatcher $dispatcher
	 * @param PKPRequest $request
	 * @param $submissionFile SubmissionFile
	 * @param int $stageId
	 */
	private function _previewAction($row, Dispatcher $dispatcher, PKPRequest $request, $submissionFile, int $stageId): void {
		import('lib.pkp.classes.linkAction.request.AjaxModal');

		$row->addAction(new LinkAction(
				'download',
				new RedirectAction (
					$dispatcher->url($request, ROUTE_COMPONENT, null, 'api.file.FileApiHandler', 'downloadFile', null,
					array(
						'submissionFileId' => $submissionFile->getData('id'),
						'submissionId'=> $submissionFile->getData('submissionId'),
						'stageId'=> $stageId
					)
				)
			),
			__('plugins.generic.filePreview.download'),
			null
		));
	}

	/**
	 * @see PKPComponentRouter::route()
	 */
	public function callBackLoadHandler($hookName, $args){
		if($args[0] === "grid.files.final.FinalDraftFilesGridHandler" && $args[1] === "filePreview"){
			$args[0] = 'plugins.generic.filePreview.classes.form.FilePreviewHandler';

			import($args[0]);
			return true;
		}
		return false;
	}
}
