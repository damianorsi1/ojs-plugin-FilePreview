{**
* plugins/generic/filePreview/templates/filePreview.tpl
*
* Copyright (c) 2023-2023 Universidad Nacional de Lanus.
* Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
*
* Document File Preview gateway plugin settings
* 
*}

<script type="text/javascript">
$(function() {ldelim}
    // Attach the form handler.
    $('#filePreviewPdfForm').pkpHandler('$.pkp.controllers.form.AjaxFormHandler');
{rdelim});
</script>

<script type="text/javascript">
$(document).ready(function() {ldelim}
    var urlBase = "{$pluginUrl}/pdf.js/web/viewer.html?file=";
    var pdfUrl = {$fileUrl|json_encode};
    {$pdfUrl}
    $("#pdfCanvasContainer > iframe").attr("src", urlBase + encodeURIComponent(pdfUrl));
{rdelim});
</script>

<div id="pdfCanvasContainer" class="galley_view">
    <iframe src="" width="100%" height="100%" style="min-height: 500px;" title="{$galleyTitle}"
    allowfullscreen webkitallowfullscreen></iframe>
</div>