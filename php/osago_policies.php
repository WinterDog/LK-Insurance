<?php
	switch ($_ACT)
	{
		case 'set_reward_sum':
			$input = get_input(array
			(
				'id'	=> 'pint',
				'sum'	=> 'uint',
			));

			$policy = Policy::get_item($input['id']);
			if (!$policy)
			{
				die('Полис не найден в базе данных!');
			}

			$policy->set_reward_sum($input['sum']);

			header('Result: 1');

			echo ($input['sum'] > 0) ? (sf\price_format($input['sum']).' р.') : '-';
			break;

		case 'set_status':
			$input = get_input(array
			(
				'id'			=> 'pint',
				'status_id'		=> 'pint',
			));

			$policy = Policy::get_item($input['id']);
			if (!$policy)
			{
				die('Полис не найден в базе данных!');
			}

			$status = $policy->set_status($input['status_id']);

			$response =
			[
				'status_title'		=> $status->title,
				'client_email'		=> $status->client_email,
			];

			header('Result: 1');
			echo json_encode($response);
			break;

		case 'send_client_email':
			$input = get_input(array
			(
				'id'			=> 'pint',
			));

			$policy = Policy::get_item($input['id']);
			if (!$policy)
			{
				die('Полис не найден в базе данных!');
			}

			$policy->send_client_email();

			header('Result: 1');
			break;

		case 'delete':
			$input = get_input(array
			(
				'id'	=> 'pint',
			));

			$policy = Policy::get_item($input['id']);
			if (!$policy)
			{
				die('Полис не найден в базе данных!');
			}

			$policy->delete();

			header('Result: 1');
			break;

		default:
			$input = get_input(
			[
				'policy_type_id'	=> 'string',
			]);

			$policies_all = Policy::get_array(
			[
				'get_create_date_ts'	=> true,
				'get_insurer'			=> true,
				'get_company'			=> true,
				'get_object'			=> true,
				'get_user'				=> true,
			]);

			$policy_sum_total = 0;
			$policy_reward_total = 0;
			$new_policy_count = array_fill(0, 6, 0);

			$policies = [];

			foreach ($policies_all as &$policy)
			{
				if ($policy->status_id == 1)
				{
					++$new_policy_count[0];
					++$new_policy_count[$policy->policy_type_id];
				}

				if (($input['policy_type_id']) && ($policy->policy_type_id != $input['policy_type_id']))
					continue;

				$policies[$policy->id] = $policy;

				$policy_sum_total += $policy->total_sum;
				$policy_reward_total += $policy->reward_sum;
			}
			unset($policy, $policies_all);

			$policy_sum_total_f = sf\price_format($policy_sum_total);
			$policy_reward_total_f = sf\price_format($policy_reward_total);

			/*
			$policies = PolicyOsago::get_array(array
			(
				'get_car'				=> true,
				'get_create_date_ts'	=> true,
				'get_insurer'			=> true,
				'get_company'			=> true,
				'get_owner'				=> true,
				'get_user'				=> true,
				'key_with_type'			=> true,
			));

			$policies = array_merge($policies, KaskoPolicy::get_array(array
			(
				'get_car'				=> true,
				'get_create_date_ts'	=> true,
				'get_insurer'			=> true,
				'get_company'			=> true,
				'get_owner'				=> true,
				'get_user'				=> true,
				'key_with_type'			=> true,
			)));
			*/

			/*
			usort($policies, function($a, $b)
			{
			    return $b->create_date_ts - $a->create_date_ts;
			});
			*/

			$_TPL = $smarty->createTemplate(TPL.'osago_policies.tpl');
			$_TPL->assign(
			[
				'new_policy_count'			=> &$new_policy_count,
				'policies'					=> &$policies,
				'policy_reward_total_f'		=> &$policy_reward_total_f,
				'policy_sum_total_f'		=> &$policy_sum_total_f,
				'policy_type_id'			=> &$input['policy_type_id'],
				'statuses'					=> PolicyStatus::get_array(),
			]);
			break;
	}
