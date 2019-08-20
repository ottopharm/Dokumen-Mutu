<?php

class GlobalComponent extends CApplicationComponent {

    /* User Menu (Tree View) */

    public function menuTree() {

        $html = '<ul id="main-menu" class="easyui-tree">
                    <li data-options="state:\'closed\'"><span>PEDOMAN MUTU</span>';
                        $sqlLevel1 = Yii::app()->db->createCommand();
                        $sqlLevel1->select('NoDokumen, JenisDokumen')
                                ->from('QA_DokLevel1')
                                ->order('NoDokumen');
                        $items = $sqlLevel1->queryAll();
        $html .= '<ul id="dok_level1">';
        if (!empty($items)) {
            foreach ($items as $item) {
                $html .= '<li id="' . $item['NoDokumen'] . '"><span><a href="javascript:void(0)"';
                $html .= ' onclick="open_tabs(\'/dokumenmutu/site/displayDokLvl1/jenisdok/' . $item['JenisDokumen'] .
                        '/nodok/' . $item['NoDokumen'] . '\',\'' . $item['NoDokumen'] . '\')">' .
                        $item['NoDokumen'] . '</a></span></li>';
            }
        }
        $html .= '</ul>';

        $html .= '</li>
        <li data-options="state:\'closed\'"><span>PROSEDUR MUTU</span>';
        
        $sqlLevel2 = Yii::app()->db->createCommand();
        $sqlLevel2->selectDistinct('a.DeptID, b.Department')
                ->from('QA_DokLevel2 a')
                ->leftJoin('Departments b', 'a.DeptID = b.DeptID')
                ->order('b.Department');
        $pms = $sqlLevel2->queryAll();
        
        if (!empty($pms)) {
            $html .= '<ul id="dok_level2_dept">';
            foreach ($pms as $pm) {
                $html .= '<li data-options="state:\'closed\'"><span>' .
                        $pm['Department'] . '</span>';

                $sqlChild = Yii::app()->db->createCommand();
                $sqlChild->select('NoDokumen')
                        ->from('QA_DokLevel2')
                        ->where('DeptID=:deptId', array(':deptId' => $pm['DeptID']))
                        ->order('NoDokumen');
                $ims = $sqlChild->queryAll();
                
                if( !empty($ims) ) {
                    $html .= '<ul id="dok_level2_nodok">';
                    foreach ($ims as $im) {
                        $html .= '<li><span><a href="javascript:void(0)"';
                        $html .= ' onclick="open_tabs(\'/dokumenmutu/site/displayDokLvl2/dept/' . $pm['DeptID'] .
                                '/folder/' . $im['NoDokumen'] . '\',\'' . $im['NoDokumen'] . '\')">' .
                                $im['NoDokumen'] . '</a></span></li>';
                    } 
                    $html .= '</ul>';
                }
                $html .= '</li>';
            }
            $html .= '</ul>';
        }
        $html .= '</li>
                        
        <li data-options="state:\'closed\'"><span>PROTAP & FORMULIR</span>';
        $sqlParent = Yii::app()->db->createCommand();
        $sqlParent->selectDistinct('a.DeptID, b.Department')
                ->from('QA_DokLevel3 a')
                ->leftJoin('Departments b', 'a.DeptID = b.DeptID')
                ->order('b.Department');

        $pms = $sqlParent->queryAll();
        if (!empty($pms)) {
            $html .= '<ul id="dok_level3_dept">';
            foreach ($pms as $pm) {
                $html .= '<li data-options="state:\'closed\'"><span>' .
                        $pm['Department'] . '</span>';

                $sqlChild = Yii::app()->db->createCommand();
                $sqlChild->selectDistinct('JenisDokumen')
                        ->from('QA_DokLevel3')
                        ->where('DeptID=:deptID', array(':deptID' => $pm['DeptID']))
                        ->order('JenisDokumen');

                $cms = $sqlChild->queryAll();
                if (!empty($cms)) {
                    $html .= '<ul id="dok_level3_jenisdok">';
                    foreach ($cms as $cm) {
                        $html .= '<li data-options="state:\'closed\'"><span>' .
                                $cm['JenisDokumen'] . '</span>';

                        $sqlItem = Yii::app()->db->createCommand();
                        $sqlItem->select('NoDokumen, FileExt')
                                ->from('QA_DokLevel3')
                                ->andWhere('JenisDokumen=:jenisDok', array(':jenisDok' => $cm['JenisDokumen']))
                                ->andWhere('DeptID=:deptID', array(':deptID' => $pm['DeptID']))
                                ->order('NoDokumen');
                        $ims = $sqlItem->queryAll();

                        if (!empty($ims)) {
                            $html .= '<ul id="dok_level3_nodok">';
                            foreach ($ims as $im) {
                                $ext = is_null($im['FileExt']) ? 'pdf' : $im['FileExt'];
                                $html .= '<li><span><a href="javascript:void(0)"';
                                $html .= ' onclick="open_tabs(\'/dokumenmutu/site/displayDokLvl3/dept/' . $pm['DeptID'] .
                                        '/jenisdok/' . $cm['JenisDokumen'] .
                                        '/folder/' . $im['NoDokumen'] . '/ext/' . $ext . '\',\'' . $im['NoDokumen'] . '\')">' .
                                        $im['NoDokumen'] . '</a></span></li>';
                            }
                            $html .= '</ul>';
                        }
                        $html .= '</li>';
                    }
                    $html .= '</ul>';
                }
                $html .= '</li>';
            }
            $html .= '</ul></li>';
        }
        $html .= '<li><span>
                    <a href="' . Yii::app()->baseUrl . '/admin/index" class="easyui-tree" style="color: #fff">Admin Page</a>
                  </span></li>';
        $html .= '</ul>';
        return $html;
    }
    
    public function adminMenuTree() {
        $html = '<ul id="main-menu" class="easyui-tree">' .
                    '<li data-options="state:\'closed\'"><span>UPLOAD DOKUMEN</span>
                        <ul>
                            <li><span>
                                <a href="javascript:void(0)" 
                                        onclick="open_tabs(\'/dokumenmutu/dokLevel1/index\',
                                        \'Dokumen Pedoman Mutu\')">Pedoman Mutu
                                </a></span>
                            </li>
                            <li><span>
                                <a href="javascript:void(0)" 
                                        onclick="open_tabs(\'/dokumenmutu/dokLevel2/index\',
                                        \'Dokumen Prosedur Mutu\')">Prosedur Mutu
                                </a></span>
                            </li>
                            <li><span>
                                <a href="javascript:void(0)" 
                                        onclick="open_tabs(\'/dokumenmutu/dokLevel3/index\',
                                        \'Dokumen Protap & Formulir\')">Protap & Formulir
                                </a></span>
                            </li>
                        </ul>
                    </li>';
        $role = Yii::app()->session['loginSession']['role'];
        if( !empty($role) && $role == "superuser" ) {
            $html .= '<li><span>
                        <a href="javascript:void(0)" 
                            onclick="open_tabs(\'/dokumenmutu/departments/index\',
                            \'Master Department\')">Master Department
                        </a></span>
                    </li> 
                    <li><span>
                        <a href="javascript:void(0)" 
                            onclick="open_tabs(\'/dokumenmutu/user/index\',
                            \'Master User\')">Master User
                        </a></span>
                    </li>';
        }
       
        $html .= '</ul>';
        
        return $html;
    }
    
    public static function delTree($dir) {
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? self::delTree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }

}
