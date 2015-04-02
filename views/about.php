<?php

/**
 * Copyright 2014 Michael van Vliet (Leiden University), Thomas Hankemeier 
 * (Leiden University)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 * 		http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 **/

	require_once('header.php');
?>
	<header>
		<div style="float:right"><a target="_blank" href="/rss/?limit=15"><img src="/img/feed-icon-28x28.png"></a></div>		
		<h2>About us</h2>
	</header>

<strong>MetabolomeXchange collaborative agreement, Version 1.3, 21 March 2015</strong>

<p>MetabolomeXchange (MetX) is an international collaboration of metabolomics data repositories that handle public submissions ('Database Providers'). This data exchange covers both the exchange of study data as well as reference data  for individual metabolites and metabolomic profiles. All Database Providers pledge to provide primary data and metadata according to the following guidelines. These guidelines are not intended to be legally binding, but to define the mode of interaction among collaborating Database Providers.</p>

<h3>1. Aims</h3>
<p>
1.1. To provide a network of stable, coordinated, freely accessible metabolomics data from repositories that handle public submissions.<br />
1.2. To jointly make all published metabolomics research data easily accessible for the scientific as well as commercial user community.<br />
1.3. To provide easy access to metabolomic reference data and work together to close the gaps in reference data provision.<br />
1.4. To work closely with publishers, instrument vendors, software developers, data generation facilities, international data standardization efforts, the Metabolomics Society  and the user community in the field of metabolomics to promote data accessibility.
</p>

<h3>2. Membership</h3>
<p>
2.1. The MetabolomeXchange Consortium consists of database and infrastructure providers: The MetX partner commits to participate in the Consortium, by maintaining a major resource or infrastructure for metabolomics data that fulfils all of the data provisioning guidelines of the Consortium and the conditions of active Database Providership described below.<br />
2.2. The MetX consortium are supported by an Advisory Board. This Advisory Board will have representatives from relevant international organisation and geographic regions world-wide. A complete list of advisors is available on the MetabolomeXchange website.
</p>

<h3>3. Decision making process</h3>
<p>
3.1. Decisions about procedures and membership will be made by the database providers in the MetabolomeXchange, after consulting all members of the consortium.<br />
3.2. Membership of the the Advisory Board is decided by the MetX partners in an annual consortium meeting.
</p>

<h3>4. Data provisioning</h3>
<p>
4.1. Database Providers must implement "public" and "private" data access mechanisms. Private data access will usually be for pre-publication data, public data access for post-publication data, or if the authors permit pre-publication access. A typical use case for private data access is for reviewers of manuscripts referring to a data set.<br />
4.2. On publication of a manuscript, the associated MetX dataset must become publicly accessible with minimal delay, but no more than 30 days.<br />
4.3. Once released, all data must be and remain fully freely and publicly accessible to all potential user groups, without additional steps like user registration or limitation of access for example only to academic users.<br />
4.4. Database Providers must implement mechanisms for download of datasets to enable re-use of data.<br />
4.5. Database Providers must implement mechanisms to ensure that ethically sensitive data are handled according to national and international privacy protection law.
</p>

<h3>5. End of Database Providership</h3>
<p>
5.1. Database Providers may leave the Consortium at any time by notification to the other Database Providers.<br />
5.2. Leaving Database Providers must make all their data records available for import by an active partner database, for a 12-month period following departure, such that they may continue to be made searchable via the MetabolomeXchange portal (changing the underlying URLs). The importing database will then actively maintain these records but will acknowledge the originating database within the record.<br />
5.3. If an active partner is not fulfilling the conditions of active Database Providership, the other Database Providers may vote to issue a formal warning. If the issues remain unresolved after six months, the other active Database Providers may vote to terminate the MetX Database Providership of the partner in question.
</p>

<h3>6. Steps for joining the Consortium</h3>
<p>
6.1. The applicants must contact all MetX Database Providers expressing their desire to join the Consortium. They must submit a document including the description of the resource (including the data workflow(s) they would like to support), with URLs to live site, and available resources, in particular curators, as well as backup strategies.<br />
6.2. The MetX Database Providers will review the document and the resource, and ask for further details if needed. The document will be refined through iterations until both the applicant and the MetX Database Providers agree in a final version. If the applicant cannot meet the criteria for joining the Consortium, the existing Database Providers may vote to decline the application.<br />
6.3. In parallel, the applicant must be able to create a MetX XML file for their first planned submission and must send it to all current Database Providers for review.<br />
6.4. Database Providers will discuss the MetX XML file with the applicant and iterate with the applicant to ensure that the document conforms to MetabolomeXchange agreed standards.<br />
6.5. All current Database Providers vote to approve the application according to the voting guidelines. If a vote does not pass, the existing Database Providers must draft a response letter to the applicant that indicates what the path to passing might be or if there is no path to acceptance.<br />
6.6. The applicant is given test access to the Metabolome Central web service and may perform test ID requests and test submissions.<br />
6.7. In parallel, the external documentation of the MetX consortium must be updated to include the new resource. Once it is ready, the documentation will be made available at the MetX web page (http://www.metabolomexchange.org).<br />
6.8. When the MetX Database Providers and applicant both agree in that the external documentation is ready and the software communication between Metabolome Central and the applicant are satisfactory, the new partner will be given full production status and production submissions may commence.
</p>

<h3>7. Revision of the document</h3>
<p>
7.1. This document may be revised at any time such that the approval of changes follows the voting guidelines described above.
</p>


<?php 

	require_once('footer.php');
