<?php

/**
 * Solution functions
 *
 * Functions that allow the unit tests to parse an icoming request, identify
 * dates with a specific number of scores, identify users with highscore by
 * date, and identify dates when a user was in a top percentile
 *
 * PHP version 7
 *
 * @author     James Webb <jameswebbdev@gmail.com>
 * @link       https://github.com/melvinmt/php-challenge-2/blob/master/solution.php
 * @since      File available since Release 1.0.0
 */

/**
 * Parse request
 *
 * @param $request
 * @param $secret
 * @return
 */
function parse_request($request, $secret)
{
    // YOUR CODE GOES HERE
}

/**
 * List of dates with at least a specific number of scores
 *
 * @param $pdo
 * @param $n
 * @return
 */
function dates_with_at_least_n_scores($pdo, $n)
{
    // YOUR CODE GOES HERE
}

/**
 * List of users with top score on date
 *
 * @param $request
 * @param $secret
 * @return
 */
function users_with_top_score_on_date($pdo, $date)
{
    // YOUR CODE GOES HERE
}

/**
 * List of dates when user was in specified top percentile
 *
 * @param $request
 * @param $secret
 * @return
 */
function dates_when_user_was_in_top_n($pdo, $user_id, $n)
{
    // YOUR CODE GOES HERE
}
