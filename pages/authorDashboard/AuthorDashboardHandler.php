<?php

/**
 * @file pages/authorDashboard/AuthorDashboardHandler.php
 *
 * Copyright (c) 2014-2021 Simon Fraser University
 * Copyright (c) 2003-2021 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class AuthorDashboardHandler
 * @ingroup pages_authorDashboard
 *
 * @brief Handle requests for the author dashboard.
 */

namespace APP\pages\authorDashboard;

use PKP\core\PKPApplication;
use PKP\pages\authorDashboard\PKPAuthorDashboardHandler;

class AuthorDashboardHandler extends PKPAuthorDashboardHandler
{
    /**
     * @copydoc PKPAuthorDashboardHandler::_getRepresentationsGridUrl()
     */
    protected function _getRepresentationsGridUrl($request, $submission)
    {
        return $request->getDispatcher()->url(
            $request,
            PKPApplication::ROUTE_COMPONENT,
            null,
            'grid.articleGalleys.ArticleGalleyGridHandler',
            'fetchGrid',
            null,
            [
                'submissionId' => $submission->getId(),
                'publicationId' => '__publicationId__',
            ]
        );
    }
}
