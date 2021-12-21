package org.curriki.api.enus.base;

import java.text.Normalizer;
import java.time.ZoneId;
import java.time.ZonedDateTime;
import java.util.List;

import org.apache.commons.lang3.StringUtils;
import org.apache.commons.lang3.exception.ExceptionUtils;
import org.apache.commons.lang3.reflect.FieldUtils;

import org.curriki.api.enus.config.ConfigKeys;
import org.curriki.api.enus.request.SiteRequestEnUS;
import org.curriki.api.enus.wrap.Wrap;

/**
 * Indexed: true
 * Page: true
 * SuperPage: PageLayout
 * 
 * Keyword: classSimpleNameCluster
 * Map.hackathonMission: to create a new Java class BaseModel to serve as the base persistent object for primary key, created and modified dates and other useful base model data. 
 * Map.hackathonColumn: Develop Base Classes
 * Map.hackathonLabels: Java
 * Map.hackathonMissionGen: to create a generated Java class that can be extended and override these methods to serve as the base persistent object for primary key, created and modified dates and other useful base model data. 
 * Map.hackathonColumnGen: Develop Base Classes
 * Map.hackathonLabelsGen: Java
 */     
public class BaseModel extends BaseModelGen<Object> {

	/**
	 * {@inheritDoc}
	 * Ignore: true
	 */
	protected void _siteRequest_(Wrap<SiteRequestEnUS> w) {}

	/**
	 * {@inheritDoc}
	 * DocValues: true
	 * PrimaryKey: true
	 * Modify: false
	 * HtmlRow: 1
	 * HtmlCell: 1
	 * DisplayName.enUS: primary key
	 */
	protected void _pk(Wrap<Long> w) {}

	/**
	 * {@inheritDoc}
	 * DocValues: true
	 * InheritPrimaryKey: true
	 * Define: true
	 */
	protected void _inheritPk(Wrap<String> w) {}

	/**
	 * {@inheritDoc}
	 * UniqueKey: true
	 */
	protected void _id(Wrap<String> w) {
		if(pk != null)
			w.o(getClass().getSimpleName() + "_" + pk.toString());
	}

	/**
	 * {@inheritDoc}
	 * DocValues: true
	 * Define: true
	 * Modify: false
	 * VarCreated: true
	 * HtmlRow: 1
	 * HtmlCell: 2
	 * HtmlColumn: 2
	 * DisplayName.enUS: created
	 */
	protected void _created(Wrap<ZonedDateTime> w) {}

	/**
	 * {@inheritDoc}
	 * DocValues: true
	 * Modify: false
	 * VarModified: true
	 * HtmlRow: 1
	 * HtmlCell: 3
	 * DisplayName.enUS: modified
	 */ 
	protected void _modified(Wrap<ZonedDateTime> w) {
		w.o(ZonedDateTime.now(ZoneId.of(siteRequest_.getConfig().getString(ConfigKeys.SITE_ZONE))));
	}

	/**
	 * {@inheritDoc}
	 * DocValues: true
	 * Define: true
	 * HtmlRow: 2
	 * HtmlCell: 1
	 * DisplayName.enUS: archived
	 */ 
	protected void _archived(Wrap<Boolean> w) {
		w.o(false);
	}

	/**
	 * {@inheritDoc}
	 * DocValues: true
	 * Define: true
	 * HtmlRow: 2
	 * HtmlCell: 2
	 * DisplayName.enUS: deleted
	 */ 
	protected void _deleted(Wrap<Boolean> w) {
		w.o(false);
	}

	/**
	 * {@inheritDoc}
	 * DocValues: true
	 */ 
	protected void _classCanonicalName(Wrap<String> w) {
		w.o(getClass().getCanonicalName());
	}

	/**
	 * {@inheritDoc}
	 * DocValues: true
	 */ 
	protected void _classSimpleName(Wrap<String> w) {
		w.o(getClass().getSimpleName());
	}

	/**
	 * {@inheritDoc}
	 * DocValues: true
	 * Define: true
	 * Modify: false
	 */ 
	protected void _sessionId(Wrap<String> w) {
	}

	/**   
	 * {@inheritDoc}
	 * Var.enUS: userKey
	 * DocValues: true
	 * Define: true
	 * Modify: false
	 */                 
	protected void _userKey(Wrap<Long> c) {
	}
	/**
	 * {@inheritDoc}
	 * DocValues: true
	 * Saves: true
	 */ 
	protected void _saves(List<String> l) {
	}

	/**
	 * {@inheritDoc}
	 * DocValues: true
	 * VarTitle: true
	 * HtmlColumn: 2
	 */  
	protected void _objectTitle(Wrap<String> w) {
		w.o(toString());
	}

	/**
	 * {@inheritDoc}
	 * DocValues: true
	 * VarId: true
	 * HtmlRow: 1
	 * HtmlCell: 4
	 * DisplayName.enUS: ID
	 */ 
	protected void _objectId(Wrap<String> w) {
		if(objectTitle != null) {
			w.o(toId(objectTitle));
		}
		else if(pk != null){
			w.o(pk.toString());
		}
	}

	public String toId(String s) {
		if(s != null) {
			s = Normalizer.normalize(s, Normalizer.Form.NFD);
			s = StringUtils.lowerCase(s);
			s = StringUtils.trim(s);
			s = StringUtils.replacePattern(s, "\\s{1,}", "-");
			s = StringUtils.replacePattern(s, "[^\\w-]", "");
			s = StringUtils.replacePattern(s, "-{2,}", "-");
		}

		return s;
	}

	protected void _objectNameVar(Wrap<String> w) {
		if(objectId != null) {
			Class<?> cl = getClass();

			try {
				String o = toId(StringUtils.join(StringUtils.splitByCharacterTypeCamelCase((String)FieldUtils.getField(cl, cl.getSimpleName() + "_NameVar").get(this)), "-"));
				w.o(o);
			} catch (Exception e) {
				ExceptionUtils.rethrow(e);
			}
		}
	}

	/**
	 * {@inheritDoc}
	 * Suggested: true
	 */    
	protected void _objectSuggest(Wrap<String> w) { 
		w.o(toString());
	}

	/**
	 * {@inheritDoc}
	 * Text: true
	 * DocValues: true
	 */ 
	protected void _objectText(Wrap<String> w) { 
		w.o(toString());
	}

	/**
	 * {@inheritDoc}
	 * DocValues: true
	 * VarUrlId: true
	 */  
	protected void _pageUrlId(Wrap<String> w) {
		if(objectId != null) {
			String o = siteRequest_.getConfig().getString(ConfigKeys.SITE_BASE_URL) + "/" + objectNameVar + "/" + objectId;
			w.o(o);
		}
	}

	/**
	 * {@inheritDoc}
	 * DocValues: true
	 * VarUrlPk: true
	 */ 
	protected void _pageUrlPk(Wrap<String> w) {
		if(pk != null) {
			String o = siteRequest_.getConfig().getString(ConfigKeys.SITE_BASE_URL) + "/" + objectNameVar + "/" + pk;
			w.o(o);
		}
	}

	/**	
	 * {@inheritDoc}
	 * Indexe: true
	 * Stocke: true
	 **/   
	protected void _pageUrlApi(Wrap<String> w)  {
		if(pk != null) {
			String o = siteRequest_.getConfig().getString(ConfigKeys.SITE_BASE_URL) + "/api/" + objectNameVar + "/" + pk;
			w.o(o);
		}
	}
}
