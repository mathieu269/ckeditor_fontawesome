<?php

class ckeditor_fontawesome extends plxPlugin {
	
	public function __construct($default_lang) {
		
		parent::__construct($default_lang);
		
		$this->addHook('AdminPrepend', 'AdminPrepend');
		
		$this->addHook('AdminTopEndHead', 'AdminTopEndHead');
		
		$this->addHook('plxShowTemplateCss', 'plxShowTemplateCss');
		
	}
	
	
	public function plxShowTemplateCss() {
		
		$plxMotor = plxMotor::getInstance();
		
		?>
			<link rel="stylesheet" href="<?php echo $plxMotor->racine . PLX_PLUGINS;?>ckeditor/ckeditor/plugins/fontawesome/font-awesome/css/font-awesome.min.css"/>
		<?php
	}
	
	
	public function AdminPrepend() {
		
		$plxMotor = plxAdmin::getInstance();
		$this->ckeditorActif = isset($plxMotor->plxPlugins->aPlugins["ckeditor"]);
		
		if (!$this->ckeditorActif) {
			return;
		}
		
		
		// recherche des extensions de CKEditor
		
		$pluginCkeditor = $plxMotor->plxPlugins->aPlugins["ckeditor"];
		
		$extraPlugins = trim($pluginCkeditor->getParam('extraPlugins'));
		$extraPlugins = str_replace(' ', '', $extraPlugins);
		
		if ("" === $extraPlugins) {
			$tab = array();
		} else {
			$tab = explode(",", $extraPlugins);
		}
		
		// ajout de fontawesome et colordialog
		
		foreach (array("fontawesome", "colordialog") as $ckeditorPlugin) {
			if (!in_array($ckeditorPlugin, $tab)) {
				$tab[] = $ckeditorPlugin;
				$pluginCkeditor->setParam('extraPlugins', implode(",", $tab), 'cdata');
				$pluginCkeditor->saveParams();
			}
		}
		
	}
	
	
	public function AdminTopEndHead() {
		
		if (!$this->ckeditorActif) {
			return;
		}
		
		?>
		
		<script type="text/JavaScript">
		
		if (typeof CKEDITOR != 'undefined') {
			
			CKEDITOR.dtd.$removeEmpty['span'] = false;
			
			CKEDITOR.on("instanceCreated", function (e) {
				
				e.editor.on("configLoaded", function (e2) {
					
					if ("object" !== typeof(e2.editor.config.contentsCss)) {
						e2.editor.config.contentsCss = [e2.editor.config.contentsCss];
					}
					
					e2.editor.config.contentsCss.push("<?php echo PLX_PLUGINS;?>ckeditor/ckeditor/plugins/fontawesome/font-awesome/css/font-awesome.min.css");
					e2.editor.config.allowedContent = true;
					
				});
				
			});
			
		}
		
		</script>
		
		<?php
		
	}
	
}
