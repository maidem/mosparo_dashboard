..  include:: /Includes.rst.txt

.. _start:

=================
Mosparo Dashboard
=================

:Extension key:
    mosparo_dashboard

:Package name:
    maidem/mosparo-dashboard

:Version:
    |release|

:Language:
    en

:Author:
    Maik Demuth

:License:
    This document is published under the
    `Open Content License <https://www.openhub.net/licenses/opl>`__.

:Rendered:
    |today|

----

TYPO3 extension providing dashboard widgets for mosparo statistics. Shows
valid and spam submissions as well as their trend directly in the TYPO3
backend dashboard.

.. _overview:

Overview
========

The extension fetches statistics data for the configured mosparo project via
the mosparo API and exposes it as dashboard widgets:

*   **Valid submissions** — number widget, last 14 days
*   **Spam submissions** — number widget, last 14 days
*   **Mosparo submissions** — bar chart showing the daily trend of both values

.. _requirements:

Requirements
============

*   TYPO3 14+
*   PHP 8.4+
*   A reachable mosparo instance with a public/private key pair

.. _installation:

Installation
============

..  code-block:: bash

    composer require maidem/mosparo-dashboard

Afterwards, add the desired mosparo widgets in the backend under
**Dashboard → Add widget**.

.. _configuration:

Configuration
=============

Host, public key and private key are set via the extension configuration
(**System → Settings → Extension Configuration → mosparo_dashboard**) or
overridden via environment variable (e.g. in Coolify) — useful when the same
extension runs in multiple projects against different mosparo instances.

Environment variables
----------------------

*   ``MOSPARO_HOST`` (or ``MOSPARO_PUBLIC_SERVER``) — base URL of the
    mosparo instance (e.g. ``https://protect.example.com``)
*   ``MOSPARO_PUBLIC_KEY`` — public key of the mosparo project
*   ``MOSPARO_PRIVATE_KEY`` — private key of the mosparo project

These variables are read independently of ``TYPO3_CONTEXT`` and override the
extension configuration — this works both in production and locally.

.. _technical-details:

Technical details
==================

*   **MosparoStatisticService** — builds the ``Mosparo\ApiClient\Client``
    and caches the result of ``getStatisticByDate()`` for 5 minutes (own
    cache ``mosparo_dashboard``) to avoid hitting the API on every dashboard
    reload
*   **ValidSubmissionsDataProvider** / **SpamSubmissionsDataProvider** —
    provide the numbers for the ``NumberWithIconWidget`` tiles
*   **SubmissionsChartDataProvider** — prepares the daily values in
    Chart.js format for the ``BarChartWidget``
