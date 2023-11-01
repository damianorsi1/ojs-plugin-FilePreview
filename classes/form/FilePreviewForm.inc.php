<?php

/** 
 * @file FilePreviewForm. inc.plp
 *
 * Copyright (c) 2023-2023 Universidad Nacional de Lanus.
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPVING.
 *
 * @class FilePreviewForm
 * @ingroup plugins_generic_filePreview
 *
 * abrief Form for registered users to preview files
 */

import('lib.pkp.classes.form.Form');

class FilePreviewForm extends Form {

    /** @var $contextId int */
    var $contextId;

    /** @var $plugin object */
    var $plugin;

    /**
     * Constructor
     * @param $plugin object
     * @param $contextId int
     */
    function __construct($plugin, $contextId, $args) {
        $this->contextId = $contextId;
        parent::__construct($plugin->getTemplateResource('filePreview.tpl'));
    }

    /**
     * Initialize form data.
     */
    function initData(){
        $contextId = $this->contextId;
        $plugin =& $this->plugin;
    }

    /**
     *  Fetch the form.
     * @copydoc Form::fetch()
     */
    function fetch($request, $template = null, $display = false){
        $contextId = $request->getContext()->getId();

        $templateMgr = TemplateManager::getManager($request);
        $aboutUrl = $request->getDispatcher()->url($request, ROUTE_PAGE, null, 'orcidapi', 'about', null);
        $templateMgr->assign(array(
            'globallyConfigure' => "test",
            'orcidAboutUrl' => "test",
        ));
        return parent::fetch($request, $template, $display);
    }
}