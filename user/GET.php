<?php

function METHOD_GET($scope, $user_id, $client_id)
{
    $user_id = check_data($user_id, true, 'user_id', true);

    $user = sql_select('users', 'id,username,email,first_name,last_name,picture_url,created,applications,developer,admin', "id='{$user_id}'", true);

    $output = [];
    $output['id'] = (int) $user['id'];
    $output['picture_url'] = $user['picture_url'];
    $output['username'] = $user['username'];
    $output['first_name'] = $user['first_name'];
    $output['last_name'] = $user['last_name'];

    // Email
    if (scope_allowed($scope, 'email:read', false, $client_id, $user_id)) {
        $output['email'] = $user['email'];
    } else {
        $output['email'] = null;
    }

    // Applications
    if (scope_allowed($scope, 'applications:read', false, $client_id, $user_id)) {
        $output['applications'] =  json_decode($user['applications'], true);
    } else {
        $output['applications'] = null;
    }

    $output['developer'] = (bool) $user['developer'];
    $output['admin'] = (bool) $user['admin'];
    $output['created'] = $user['created'];

    log_action('1', 'user.info', $_SERVER["REMOTE_ADDR"], $user_id, $client_id);
    return $output;
}
