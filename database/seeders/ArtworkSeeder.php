<?php

namespace Database\Seeders;

use A17\Twill\Models\User;
use App\Libraries\Api\Builders\ApiQueryBuilder;
use App\Models\ThemePrompt;
use App\Repositories\ArtworkRepository;
use App\Repositories\ThemePromptArtworkRepository;
use Database\Seeders\Behaviors\HasTwillSeeding;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ArtworkSeeder extends Seeder
{
    use HasTwillSeeding;

    private ApiQueryBuilder $api;

    public function __construct()
    {
        $this->api = app(ApiQueryBuilder::class);
    }

    public function run(ThemePrompt $themePrompt, array $artworks): void
    {
        collect($artworks)->each(function ($rawArtwork) use ($themePrompt) {

            $apiFields = $this->getApiFields(
                $this->getApiId($rawArtwork['id'])
            );

            if (! $apiFields) {
                $this->command->warn('Artwork not found in API: '.$rawArtwork['id']);

                return;
            }

            if (! $rawArtwork['translations']) {
                $this->command->warn('No translations found for: '.$rawArtwork['id'].' - '.$rawArtwork['title']);
            }

            $artworkData = collect([
                'en' => [
                    'title' => $rawArtwork['title'],
                    'artist' => $rawArtwork['artist'],
                    'locationDirections' => $rawArtwork['locationDirections'] ?? null,
                ],
            ])->merge($rawArtwork['translations'])->map(
                fn ($translation, $locale) => [
                    'title' => [$locale => $translation['title']],
                    'artist' => [$locale => $translation['artist']],
                    'location_directions' => [$locale => $translation['locationDirections'] ?? null],
                ]
            )->reduce(function (array $carry, array $translation) {
                return array_merge_recursive($carry, $translation);
            }, []);

            $artwork = app()->make(ArtworkRepository::class)->firstOrCreate(
                ['datahub_id' => $apiFields['datahub_id']],
                ['published' => true, ...$artworkData, ...$apiFields]
            );

            if ($artwork->wasRecentlyCreated) {
                activity()->performedOn($artwork)->causedBy(User::find(1))->log('created');
            }

            $artwork->translations()->update(['active' => true]);

            $themePromptArtworkData = collect([
                'en' => [
                    'detailNarrative' => $rawArtwork['detailNarrative'] ?? null,
                    'viewingDescription' => $rawArtwork['viewingDescription'] ?? null,
                    'activityInstructions' => $rawArtwork['activityInstructions'] ?? null,
                ],
            ])->merge($rawArtwork['translations'])->map(
                fn ($translation, $locale) => [
                    'detail_narrative' => [$locale => $translation['detailNarrative'] ?? null],
                    'viewing_description' => [$locale => $translation['viewingDescription'] ?? null],
                    'activity_instructions' => [$locale => $translation['activityInstructions'] ?? null],
                ]
            )->reduce(function (array $carry, array $translation) {
                return array_merge_recursive($carry, $translation);
            }, []);

            $themePromptArtwork = app()->make(ThemePromptArtworkRepository::class)->create([
                ...$themePromptArtworkData,
                'title' => $rawArtwork['title'],
                'theme_prompt_id' => $themePrompt->id,
                'artwork_id' => $artwork->id,
                'activity_template' => $rawArtwork['activityTemplate'],
            ]);

            activity()->performedOn($themePromptArtwork)->causedBy(User::find(1))->log('created');

            $themePromptArtwork->translations()->update(['active' => true]);
        });
    }

    private function getApiFields(?int $id): array
    {
        if (! $id) {
            return [];
        }

        try {
            return $this->api
                ->get(endpoint: '/api/v1/artworks/'.$id)
                ->map(fn ($artwork) => [
                    'datahub_id' => $artwork->id,
                    ...Arr::only((array) $artwork, [
                        'is_on_view',
                        'image_id',
                    ]),
                ])
                ->first() ?? [];
        } catch (Exception) {
            return [];
        }
    }

    private function getApiId(int $id): ?int
    {
        $data = [
            362 => 156538,
            363 => 45350,
            364 => 100858,
            365 => 27992,
            366 => 197585,
            367 => 17856,
            368 => 206379,
            369 => 25062,
            370 => 65244,
            371 => 28067,
            372 => 146930,
            373 => 151424,
            374 => 52201,
            375 => 109417,
            376 => 209926,
            377 => 111459,
            378 => 154023,
            379 => 212967,
            380 => 154236,
            381 => 196410,
            382 => 118733,
            383 => 88419,
            384 => 99761,
            385 => 152428,
            386 => 181738,
            387 => 140644,
            388 => 21023,
            389 => 6565,
            390 => 111442,
            391 => 7500,
            392 => 656,
            393 => 80607,
            394 => 229406,
            395 => 68769,
            396 => 105203,
            397 => 57819,
            398 => 189715,
            399 => 126436,
            400 => 148333,
            401 => 185905,
            402 => 208516,
            404 => 62042,
            405 => 99539,
            406 => 148369,
            407 => 82306,
            408 => 28869,
            409 => 45407,
            410 => 181778,
            411 => 105800,
            412 => 110246,
            413 => 44018,
            414 => 81503,
            415 => 62452,
            416 => 60510,
            417 => 207293,
            418 => 125660,
            419 => 200696,
            420 => 118981,
            421 => 66042,
            422 => 34181,
            423 => 17937,
            425 => 146961,
            426 => 75644,
            427 => 218612,
            428 => 102105,
            429 => 100350,
            430 => 81533,
            431 => 65887,
            432 => 91573,
            433 => 12786,
            434 => 185766,
            435 => 152753,
            436 => 140644,
            437 => 151361,
            438 => 86423,
            439 => 6565,
            440 => 146940,
            441 => 229376,
            442 => 151363,
            443 => 207740,
            445 => 117266,
            446 => 129884,
            447 => 110246,
            448 => 83642,
            449 => 132674,
            450 => 73795,
            451 => 561,
            452 => 151363,
            453 => 90864,
            454 => 111629,
            455 => 229375,
            456 => 229376,
            457 => 28961,
            458 => 34145,
            459 => 80499,
            460 => 656,
            461 => 94133,
            462 => 111377,
            463 => 156596,
            464 => 21682,
            465 => 40887,
            466 => 21727,
            467 => 148369,
            468 => 31577,
            469 => 151424,
            470 => 93798,
            471 => 117330,
            472 => 148357,
            473 => 20579,
            474 => 189807,
            475 => 75644,
            476 => 56682,
            477 => 28560,
            478 => 154236,
            479 => 45350,
            480 => 2816,
            481 => 148333,
            482 => 73654,
            483 => 111628,
            485 => 34116,
            486 => 57996,
            487 => 72728,
            488 => 15468,
            489 => 28560,
            490 => 229379,
            491 => 129639,
            492 => 129906,
            493 => 151424,
            494 => 60510,
            495 => 200699,
            496 => 148333,
            497 => 99539,
            498 => 195461,
            499 => 656,
            500 => 146875,
            501 => 111648,
            502 => 91598,
            503 => 74445,
            504 => 53495,
            505 => 189807,
            506 => 117330,
            507 => 55905,
            508 => 91377,
            509 => 190409,
            510 => 136952,
            511 => 79382,
            512 => 80898,
            513 => 35376,
            514 => 126436,
            515 => 229375,
            516 => 641,
            517 => 86366,
            518 => 189595,
            519 => 160172,
            520 => 185905,
            521 => 100827,
            522 => 27992,
            523 => 229376,
            525 => 20684,
            526 => 56682,
            527 => 109938,
            528 => 72728,
            529 => 17435,
            530 => 57996,
            531 => 76244,
            532 => 146875,
            533 => 148369,
            534 => 16495,
            535 => 154235,
            536 => 109439,
            537 => 72864,
            538 => 31672,
            539 => 154496,
            540 => 34116,
            541 => 117330,
            542 => 129906,
            543 => 7021,
            544 => 95998,
            545 => 100666,
            546 => 84241,
            547 => 91377,
            548 => 190409,
            549 => 31577,
            550 => 118551,
            551 => 100858,
            552 => 14551,
            553 => 93798,
            554 => 151424,
            555 => 151358,
            556 => 200696,
            557 => 15468,
            558 => 2446,
            559 => 20579,
            560 => 148357,
            561 => 32532,
            562 => 4796,
            563 => 86385,
            564 => 120818,
            566 => 32532,
            567 => 5580,
            568 => 24332,
            569 => 22525,
            570 => 187061,
            571 => 121377,
            572 => 136120,
            573 => 30839,
            574 => 73661,
            575 => 55249,
            576 => 146961,
            577 => 109317,
            578 => 109924,
            579 => 106372,
            580 => 86423,
            581 => 151363,
            582 => 120207,
            583 => 79763,
            585 => 99769,
            586 => 28869,
            587 => 8864,
            588 => 34181,
            589 => 208516,
            590 => 197714,
            591 => 185905,
            592 => 215092,
            593 => 68769,
            594 => 152750,
            595 => 159135,
            596 => 34286,
            597 => 57819,
            598 => 86385,
            599 => 137226,
            600 => 105800,
            601 => 27992,
            602 => 111628,
            603 => 9503,
            604 => 44084,
            605 => 97933,
            616 => 10509,
            617 => 71090,
            618 => 229369,
            619 => 91361,
            621 => 60623,
            624 => 73040,
            625 => 80607,
            626 => 99539,
            627 => 229363,
            628 => 229393,
            629 => 109819,
            630 => 69576,
            631 => 86467,
            632 => 148334,
            633 => 220627,
            634 => 84742,
            635 => 229355,
            636 => 152761,
            637 => 117835,
            638 => 125660,
            639 => 102581,
            640 => 181145,
            641 => 87088,
            642 => 220272,
            643 => 83642,
            644 => 229364,
            645 => 188510,
            646 => 93779,
            647 => 186680,
            648 => 76244,
            649 => 36129,
            650 => 154023,
            651 => 111666,
            652 => 215092,
            653 => 21682,
            654 => 16617,
            655 => 35674,
            656 => 192676,
            657 => 64086,
            658 => 229370,
            659 => 181738,
            660 => 4749,
            661 => 28961,
            662 => 6565,
            663 => 5375,
            664 => 89043,
            665 => 207740,
            666 => 151361,
            667 => 55905,
            668 => 14572,
            669 => 229887,
            670 => 159135,
            671 => 106372,
            672 => 111629,
            673 => 87760,
            674 => 18751,
            675 => 73800,
            676 => 34145,
            677 => 25062,
            678 => 25062,
            679 => 30971,
            680 => 151363,
            681 => 154495,
            682 => 91893,
            683 => 229406,
            684 => 16146,
            685 => 117835,
            686 => 30732,
            687 => 229375,
            688 => 561,
            689 => 16492,
            690 => 14551,
            691 => 35376,
            692 => 189715,
            693 => 100827,
            694 => 27984,
            695 => 5580,
            696 => 154023,
            697 => 61616,
            698 => 44084,
            699 => 190409,
            700 => 222982,
            702 => 27992,
            704 => 28560,
            706 => 28560,
            708 => 52733,
            709 => 52733,
            710 => 183083,
            711 => 34145,
            712 => 86423,
            713 => 34116,
            714 => 34145,
            715 => 116353,
            716 => 156538,
            717 => 189597,
            718 => 111629,
            719 => 57996,
            720 => 76244,
            721 => 17435,
            722 => 183894,
            724 => 183894,
            725 => 35376,
            1766 => 187061,
            1767 => 121377,
            1768 => 136120,
            1769 => 30839,
            1926 => 189715,
            1927 => 92229,
            1928 => 16146,
        ];

        return $data[$id] ?? null;
    }
}
