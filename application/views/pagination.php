<?$this->pagination->create_links()?>
<button type="button" class="nav" onclick="post(\'firstPage\',true)"<?if(option('list/start')==0) echo 'disabled="disabled"'?>>&lt;&lt;</button>
<button type="button" class="nav" onclick="post(\'previousPage\',true)"<?if(option('list/start')==0) echo 'disabled="disabled"'?>>&nbsp;&lt;&nbsp;</button>
<button type="button" class="nav" onclick="post(\'nextPage\',true)"<?if(option('list/start')+option('list/items')>=$rows) echo 'disabled="disabled"'?>>&nbsp;&gt;&nbsp;</button>
<button type="button" class="nav" onclick="post(\'finalPage\',true)"<?if(option('list/start')+option('list/items')>=$rows) echo 'disabled="disabled"'?>>&gt;&gt;</button>