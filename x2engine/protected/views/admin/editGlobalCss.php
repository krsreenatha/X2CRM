<?php
/*****************************************************************************************
 * X2Engine Open Source Edition is a customer relationship management program developed by
 * X2Engine, Inc. Copyright (C) 2011-2016 X2Engine Inc.
 * 
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY X2ENGINE, X2ENGINE DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 * 
 * You can contact X2Engine, Inc. P.O. Box 66752, Scotts Valley,
 * California 95067, USA. or at email address contact@x2engine.com.
 * 
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 * 
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * X2Engine" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by X2Engine".
 *****************************************************************************************/
Yii::app()->clientScript->registerCss('editGlobalCssCSS',"

#global-css-editor + .CodeMirror {
    height: 500px;
}

");

Yii::app()->clientScript->registerScript('editGlobalCssJS',"
$(window).on ('resize', function () {
    var editor$ = $('#global-css-editor').next ();
    editor$.height ($(window).height () - editor$.offset ().top - 100);
}).resize ();
", CClientScript::POS_READY);

?>
<div class="page-title"><h2><?php echo CHtml::encode (Yii::t('admin','Edit Global CSS')); ?></h2></div>
<div class='form' id='global-css-edit-form-outer'>
<?php
if (!X2Flashes::hasFlashes ('error')) {
    $form = $this->beginWidget ('X2ActiveForm', array (
        'formModel' => $formModel
    ));
    X2Flashes::renderFlashes ('error');
    X2Flashes::renderFlashes ('success');

    echo $form->labelEx ($formModel, 'css');
    echo $form->codeEditor ($formModel, 'css', array (
        'id' => 'global-css-editor',
    ));
    echo X2Html::submitButton (Yii::t('app', 'Save Stylesheet'), array ('class' => 'x2-button'));

    $this->endWidget ();
}
?>
</div>
