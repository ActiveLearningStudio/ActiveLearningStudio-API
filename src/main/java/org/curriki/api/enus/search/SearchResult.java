package org.curriki.api.enus.search;

import org.apache.solr.common.SolrDocument;
import org.curriki.api.enus.request.SiteRequestEnUS;
import org.curriki.api.enus.wrap.Wrap;

/**
 * Keyword: classSimpleNameSearchResult
 * Map.hackathonMission: to create a new Java class to store a result from querying the search engine. 
 * Map.hackathonColumn: Develop Base Classes
 */
public class SearchResult extends SearchResultGen<Object> {

	protected void _siteRequest_(Wrap<SiteRequestEnUS> c) {
	}

	protected void _solrDocument(Wrap<SolrDocument> c) {
	}

	protected void _resultIndex(Wrap<Long> c) {
	}
}