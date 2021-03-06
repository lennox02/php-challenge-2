<?php
/**
 * Solution functions
 *
 * Functions that allow the unit tests to parse an incoming request, identify
 * dates with a specific number of scores, identify users with highscore by
 * date, and identify dates when a user was in a top range
 *
 * PHP version 5
 *
 * @author     James Webb <jameswebbdev@gmail.com>
 * @link       https://github.com/melvinmt/php-challenge-2/blob/master/solution.php
 * @since      File available since Release 1.0.0
 */


/**
 * Undoes the encryption from make_request() and parses the request into
 * original payload components
 *
 * @param $request
 * @param $secret
 * @return array|false
 */
function parse_request($request, $secret)
{
    //break the request back into components
    $request = strtr($request, '-_', '+/');
    $components = explode('.', $request);

    //decode payload
    $payload = base64_decode($components[1]);

    //decode signature and return false if not as expected
    $signature = base64_decode($components[0]);
    $expectedSignature = hash_hmac('sha256', $payload, $secret);
    if ($signature != $expectedSignature) {
        return false;
    }

    return json_decode($payload, true);

}


/**
 * List of dates with at least a specific number of scores
 *
 * @param $pdo
 * @param $n
 * @return array
 */
function dates_with_at_least_n_scores($pdo, $n)
{
    $sql = "
        SELECT date
        FROM scores
        GROUP BY date
        HAVING COUNT(*) >= :n
        ORDER BY date DESC
    ";

    $query = $pdo->prepare($sql);
    $query->bindParam(':n', $n, PDO::PARAM_INT); //bind param as int only
    $query->execute();

    return $query->fetchAll(PDO::FETCH_COLUMN);
}


/**
 * List of users with top score on date
 *
 * @param $pdo
 * @param $date
 * @return array
 */
function users_with_top_score_on_date($pdo, $date)
{
    $sql = "
        SELECT user_id
        FROM scores
        WHERE date = :date AND score = (
            SELECT MAX(score)
            FROM scores
            WHERE date = :date
            GROUP BY date
        )
    ";

    $query = $pdo->prepare($sql);
    $query->bindParam(':date', $date, PDO::PARAM_STR); //bind param as string only
    $query->execute();

    return $query->fetchAll(PDO::FETCH_COLUMN);
}


/**
 * List of dates when user was in specified top range of results
 *
 * @param $pdo
 * @param $user_id
 * @param $n
 * @return array
 */
function dates_when_user_was_in_top_n($pdo, $user_id, $n)
{
    //use date alias to see if user's score is in the top n scores where the
    //date matches the alias (allows us to loop through each date)
    $sql = "
        SELECT date as dateAlias
        FROM scores
        WHERE user_id = :user_id
        AND score IN (
            SELECT score
            FROM scores
            WHERE date = dateAlias
            ORDER BY score DESC
            LIMIT :n
        )
        ORDER BY dateAlias DESC
    ";
    $query = $pdo->prepare($sql);
    $query->bindParam(':user_id', $user_id, PDO::PARAM_INT); //bind param as int only
    $query->bindParam(':n', $n, PDO::PARAM_INT); //bind param as int only
    $query->execute();

    return $query->fetchAll(PDO::FETCH_COLUMN);
}
