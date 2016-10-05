<?php
	abstract class DBOTree extends DBObject
	{
		public $children = array();
		public $parents = array();

		/*
			Преобразование двумерного массива категорий в многомерное дерево.
		*/
		public static function array2tree($array)
		{
			// Будущее дерево.
			$tree = array();

			// Массив индексов, указывающих на "родителей" конкретного элемента.
			$parents_mas = array();

			// Этот цикл заполняет массив индексов $parents_mas.
			do
			{
				$all_elements_processed = true;

				foreach ($array as $element_id => &$element)
				{
					if (isset($parents_mas[$element_id]))
						continue;

					if ($element->parent_id)
					{
						if (!isset($parents_mas[$element->parent_id]))
							continue;

						$parents_mas[$element_id] = array_merge(array($element->parent_id), $parents_mas[$element->parent_id]);

						for ($i = sizeof($parents_mas[$element_id]) - 2; $i >= 0; $i--)
							$element->parents[$array[$parents_mas[$element_id][$i]]->id] = &$array[$parents_mas[$element_id][$i]];
					}
					else
						$parents_mas[$element_id][] = $element->parent_id;

					$all_elements_processed = false;
				}
			}
			while (!$all_elements_processed);

			// Текущий разбираемый уровень (начинаем с корневых элементов, т. е. 1-го уровня).
			$level = 1;

			do
			{
				// На текущем уровне не найдено ни одного комментария. Если переменная сохранит значение true к концу итерации, цикл можно завершать.
				$no_elements_found = true;

				// Перебираем массив комментариев.
				foreach ($array as $element_id => &$element)
				{
					if (sizeof($parents_mas[$element_id]) != $level)
						continue;

					$no_elements_found = false;

					$parent_element_link = &$tree;

					// Перебираем массив индексов родителей текущего комментария.
					for ($i = sizeof($parents_mas[$element_id]) - 2; $i >= 0; $i--)
						$parent_element_link = &$parent_element_link[$parents_mas[$element_id][$i]]->children;

					$parent_element_link[$element_id] = $element;
				}
				// Увеличиваем уровень комментариев для следующей итерации.
				$level++;
			}
			while (!$no_elements_found);

			return $tree;
		}
	}
?>