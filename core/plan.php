<?php

function trialExpired(array $user): bool
{
    if(!$user['trial_end'])
    {
        return false;
    }

    return strtotime($user['trial_end']) < time();
}