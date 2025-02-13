<?php

use App\Site\Models\User;
use Carbon\Carbon;

function static_addnewachievement(int $id): void
{
    $query = "UPDATE StaticData AS sd ";
    $query .= "SET sd.NumAchievements=sd.NumAchievements+1, sd.LastCreatedAchievementID='$id'";
    $dbResult = s_mysql_query($query);
    if (!$dbResult) {
        log_sql_fail();
    }
}

function static_addnewgame(int $id): void
{
    // Subquery to get # of games that have achievements
    $query = "UPDATE StaticData AS sd ";
    $query .= "SET sd.NumGames = (SELECT COUNT(DISTINCT ach.GameID) ";
    $query .= "                   FROM GameData gd ";
    $query .= "                   INNER JOIN Achievements ach ON ach.GameID = gd.ID), sd.LastCreatedGameID = '$id'";
    // $query = "UPDATE StaticData AS sd ";
    // $query .= "SET sd.NumGames = sd.NumGames+1, sd.LastCreatedGameID = '$id'";
    $dbResult = s_mysql_query($query);
    if (!$dbResult) {
        log_sql_fail();
    }
}

function static_addnewregistereduser(string $user): void
{
    sanitize_sql_inputs($user);

    $query = "UPDATE StaticData AS sd ";
    $query .= "SET sd.NumRegisteredUsers = sd.NumRegisteredUsers+1, sd.LastRegisteredUser = '$user', sd.LastRegisteredUserAt = NOW()";
    $dbResult = s_mysql_query($query);
    if (!$dbResult) {
        log_sql_fail();
    }
}

function static_addnewhardcoremastery(int $gameId, string $username): void
{
    $foundUser = User::firstWhere('User', $username);
    if ($foundUser->Untracked) {
        return;
    }

    $query = "UPDATE StaticData
        SET
            num_hardcore_mastery_awards = num_hardcore_mastery_awards+1,
            last_game_hardcore_mastered_game_id = :gameId,
            last_game_hardcore_mastered_user_id = :userId,
            last_game_hardcore_mastered_at = :now
    ";

    legacyDbStatement($query, ['gameId' => $gameId, 'userId' => $foundUser->ID, 'now' => Carbon::now()]);
}

function static_addnewhardcoregamebeaten(int $gameId, string $username): void
{
    $foundUser = User::firstWhere('User', $username);
    if ($foundUser->Untracked) {
        return;
    }

    $query = "UPDATE StaticData
        SET
            num_hardcore_game_beaten_awards = num_hardcore_game_beaten_awards+1,
            last_game_hardcore_beaten_game_id = :gameId,
            last_game_hardcore_beaten_user_id = :userId,
            last_game_hardcore_beaten_at = :now
    ";

    legacyDbStatement($query, ['gameId' => $gameId, 'userId' => $foundUser->ID, 'now' => Carbon::now()]);
}

function static_setlastearnedachievement(int $id, string $user, int $points): void
{
    $query = "UPDATE StaticData
              SET NumAwarded = NumAwarded+1,
                  LastAchievementEarnedID = $id,
                  LastAchievementEarnedByUser = :user,
                  LastAchievementEarnedAt = :now,
                  TotalPointsEarned = TotalPointsEarned+$points";
    $dbResult = legacyDbStatement($query, ['user' => $user, 'now' => Carbon::now()]);
    if (!$dbResult) {
        log_sql_fail();
    }
}

function static_setlastupdatedgame(int $id): void
{
    $query = "UPDATE StaticData AS sd ";
    $query .= "SET sd.LastUpdatedGameID = '$id'";
    $dbResult = s_mysql_query($query);
    if (!$dbResult) {
        log_sql_fail();
    }
}

function static_setlastupdatedachievement(int $id): void
{
    $query = "UPDATE StaticData AS sd ";
    $query .= "SET sd.LastUpdatedAchievementID = '$id'";
    $dbResult = s_mysql_query($query);
    if (!$dbResult) {
        log_sql_fail();
    }
}
