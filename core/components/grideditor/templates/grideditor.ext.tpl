{literal}
<script type="text/javascript">
    Ext.onReady(function() {
        (function(){
            MODx.load({ {/literal}
                    xtype: 'grideditor-grid-grideditor'
                    ,renderTo: '{$grideditor->renderTo}'
                    ,grideditor: {$grideditor->conf}
                    {literal}
                });
        }).defer(500);
    });
</script>
{/literal}