<?php
	class Cart
	{
		public function __construct(
			&$cart_content)
		{
			if (!isset($cart_content))
				$cart_content = '';

			$this->items = array();
			$this->sum_total = 0;
			$this->items_total = 0;

			$items = json_decode($cart_content, true);

			if (is_array($items))
			{
				foreach ($items as $item_id => &$item_qty)
				{
					$item = ShopItem::get_item($item_id);

					if (!$item)
						continue;

					$item->qty = $item_qty;
					$item->sum = $item->price * $item->qty;

					$this->sum_total += $item->sum;
					$this->items_total += $item->qty;

					$this->items[$item_id] = $item;
				}
				unset($item_qty);
			}
		}

		public function get_content()
		{
			global $smarty;

			$tpl = $smarty->createTemplate('shop_cart_content.tpl');
			$tpl->assign(array
			(
				'cart'	=> $this
			));
			return $tpl->fetch();
		}
	}
?>