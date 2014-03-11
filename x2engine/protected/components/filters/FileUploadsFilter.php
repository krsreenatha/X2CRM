<?php

/*****************************************************************************************
 * X2Engine Open Source Edition is a customer relationship management program developed by
 * X2Engine, Inc. Copyright (C) 2011-2014 X2Engine Inc.
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

/**
 * Checks uploaded files in the web request for invalid extensions.
 *
 * Intended as a catch-all for attempted arbitrary file type uploads.
 * 
 * @package application.components.filters
 * @author Demitri Morgan <demitri@x2engine.com>
 */
class FileUploadsFilter extends CFilter {

    /**
     * Regular expression for blacklisted files.
     */
    const EXT_BLACKLIST = '/\.\s*(?P<ext>html|htm|js|jsb|mhtml|mht|xhtml|xht|php|phtml|php3|php4|php5|phps|shtml|jhtml|pl|py|cgi|exe|scr|dll|msi|vbs|bat|com|pif|cmd|vxd|cpl|ini|conf|cnf|key|iv)\s*$/';

    /**
     * List of mime-types that uploaded files should never have
     * @var type
     */
    private $_mimeBlacklist = array(
        'text/html', 'text/javascript', 'text/x-javascript',
        'application/x-shellscript', 'application/x-php', 'text/x-php',
        'text/x-python', 'text/x-perl', 'text/x-bash', 'text/x-sh',
        'text/x-csh', 'text/scriptlet', 'application/x-msdownload',
        'application/x-msmetafile'
    );

    /**
     * Returns true if the file is safe to upload.
     * @param array $file
     */
    public function checkFilename($filename){
        if(preg_match(self::EXT_BLACKLIST, $filename,$match)){
            throw new CHttpException(403,Yii::t('app','Forbidden file type: {ext}',array('{ext}'=>$match['ext'])));
        }
    }

    public function checkFiles(array $inputs){
   
        foreach($inputs as $fieldName => $input){
            // Structure:
            // [field name] =>
            //      'name' => [name(s)]
            //      'type' => [type(s)]
            //      'tmp_name' => [name(s)]
            //      'error' => [error(s)]
            //      'size' => [size(s)]
            if(!isset($input['name'])){
                throw new CHttpException(400, Yii::t('app', 'Uploaded files must have names.'));
            }elseif(is_array($input['name'])){
                // Multiple files in this input field
                foreach($input['name'] as $name){
                    $this->checkFileName($name);
                }
                if($forbidden = count(array_intersect($input['type'], $this->_mimeBlacklist)) > 0){
                    throw new CHttpException(403, Yii::t('app', 'List of uploaded files includes forbidden MIME types: {types}', array('{types}' => implode(',', $forbidden))));
                }
            }else{
                // One file in this input field
                $this->checkFileName($input['name']);
                if(in_array($input['type'],$this->_mimeBlacklist)) {
                    throw new CHttpException(403, Yii::t('app','Forbidden MIME type for file: {file}',array('{file}'=>$input['name'])));
                }
            }
        }
    }

    protected function preFilter($filterChain){
        if(empty($_FILES)){ // No files to be uploaded
            return true;
        }
        $this->checkFiles($_FILES);
        return true;
    }

}

?>