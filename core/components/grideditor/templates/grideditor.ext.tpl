{literal}
<script type="text/javascript">
    Ext.onReady(function() {
        MODx.load({ {/literal}
                xtype: 'grideditor-panel-cmp'
                ,renderTo: '{$grideditor->renderTo}'
                ,grideditor: {$grideditor->gridConfig->toJson()}
                {literal}
            });
    });
</script>
{/literal}
<div id="{$grideditor->renderTo}"></div>
