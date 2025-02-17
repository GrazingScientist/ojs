<?php

/**
 * @file classes/services/queryBuilders/StatsGeoQueryBuilder.php
 *
 * Copyright (c) 2022 Simon Fraser University
 * Copyright (c) 2022 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class StatsGeoQueryBuilder
 * @ingroup query_builders
 *
 * @brief Helper class to construct a query to fetch geographic stats records from the
 *  metrics_submission_geo_monthly table.
 */

namespace APP\services\queryBuilders;

use APP\submission\Submission;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use PKP\services\queryBuilders\PKPStatsGeoQueryBuilder;
use PKP\statistics\PKPStatisticsHelper;

class StatsGeoQueryBuilder extends PKPStatsGeoQueryBuilder
{
    /** The name of the section column */
    public string $sectionColumn = 'section_id';

    /** Include records for these issues */
    protected array $issueIds = [];

    /**
     * Set the issues to get records for
     */
    public function filterByIssues(array $issueIds): self
    {
        $this->issueIds = $issueIds;
        return $this;
    }

    protected function _getAppSpecificQuery(Builder &$q): void
    {
        if (!empty($this->issueIds)) {
            $issueSubmissionIds = DB::table('publications as p')->select('p.submission_id')->distinct()
                ->from('publications as p')
                ->leftJoin('publication_settings as ps', 'ps.setting_name', '=', DB::raw('\'issueId\''))
                ->where('p.status', Submission::STATUS_PUBLISHED)
                ->whereIn('ps.setting_value', $this->issueIds);
            $q->joinSub($issueSubmissionIds, 'is', function ($join) {
                $join->on('metrics_submission_geo_monthly.' . PKPStatisticsHelper::STATISTICS_DIMENSION_SUBMISSION_ID, '=', 'is.submission_id');
            });
        }
    }
}
