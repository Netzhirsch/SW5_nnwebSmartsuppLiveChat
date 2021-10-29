{extends file="parent:frontend/index/header.tpl"}

{block name="frontend_index_header_javascript_tracking"}
	{$smarty.block.parent}

	{if !empty($smartsuppKey)}
		{literal}
		<!-- Smartsupp Live Chat script -->
		<script type="text/javascript">
			var _smartsupp = _smartsupp || {};
			_smartsupp.key = '{/literal}{$smartsuppKey|escapeHtml|truncate:40}{literal}';
			_smartsupp.orientation = '{/literal}{$smartsuppChatBoxPosition|escapeHtml}{literal}';
			{/literal}{if $smartsuppHideMobileWidget == "ja"}{literal}
				_smartsupp.hideMobileWidget = true;
			{/literal}{/if}{literal}
			{/literal}{if !empty($smartsuppOffsetX)}{literal}
				_smartsupp.offsetX = '{/literal}{$smartsuppOffsetX|escapeHtml}{literal}';
			{/literal}{/if}{literal}
			{/literal}{if !empty($smartsuppOffsetY)}{literal}
				_smartsupp.offsetY = '{/literal}{$smartsuppOffsetY|escapeHtml}{literal}';
			{/literal}{/if}{literal}
			window.smartsupp||(function(d) {
				var s,c,o=smartsupp=function(){ o._.push(arguments)};o._=[];
				s=d.getElementsByTagName('script')[0];c=d.createElement('script');
				c.type='text/javascript';c.charset='utf-8';c.async=true;
				c.src='//www.smartsuppchat.com/loader.js?';s.parentNode.insertBefore(c,s);
			})(document);
			
			smartsupp('email', '{/literal}{$smartsuppEmail}{literal}');
			smartsupp('name', '{/literal}{$smartsuppName}{literal}');
			smartsupp('variables', {
				accountId: {
					label: 'Benutzer-ID',
					value: '{/literal}{$smartsuppUserId}{literal}'
				},
				customerNumber: {
					label: 'Kundennummer',
					value: '{/literal}{$smartsuppCustomerNumber}{literal}'
				},
				role: {
					label: 'Kundengruppe',
					value: '{/literal}{$smartsuppCustomerGroup}{literal}'
				},
				orderedPrice: {
					label: 'Warenkorb',
					value: '{/literal}{$smartsuppBasketAmount|number_format:2:",":"."} EUR{literal}'
				}
			});
		</script>
		{/literal}
	{/if}
{/block}