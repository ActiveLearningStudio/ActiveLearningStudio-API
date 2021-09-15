package org.curriki.api.enus.writer;

import com.fasterxml.jackson.annotation.JsonProperty;
import java.util.Arrays;
import com.fasterxml.jackson.databind.ser.std.ToStringSerializer;
import org.curriki.api.enus.base.BaseModel;
import org.curriki.api.enus.request.api.ApiRequest;
import org.curriki.api.enus.writer.AllWriter;
import org.slf4j.LoggerFactory;
import java.util.HashMap;
import org.curriki.api.enus.request.SiteRequestEnUS;
import org.apache.commons.lang3.StringUtils;
import java.text.NumberFormat;
import java.util.ArrayList;
import org.curriki.api.enus.wrap.Wrap;
import org.curriki.api.enus.java.ZonedDateTimeDeserializer;
import org.apache.commons.collections.CollectionUtils;
import com.fasterxml.jackson.databind.annotation.JsonSerialize;
import java.util.Map;
import com.fasterxml.jackson.annotation.JsonIgnore;
import java.lang.Boolean;
import org.curriki.api.enus.java.ZonedDateTimeSerializer;
import java.lang.String;
import java.math.RoundingMode;
import java.io.PrintWriter;
import org.slf4j.Logger;
import java.math.MathContext;
import java.io.StringWriter;
import io.vertx.core.Promise;
import org.apache.commons.text.StringEscapeUtils;
import com.fasterxml.jackson.annotation.JsonInclude.Include;
import com.fasterxml.jackson.annotation.JsonFormat;
import org.curriki.api.enus.config.ConfigKeys;
import io.vertx.core.Future;
import java.io.File;
import java.util.Objects;
import io.vertx.core.json.JsonArray;
import io.vertx.core.buffer.Buffer;
import org.apache.commons.lang3.math.NumberUtils;
import java.util.Optional;
import com.fasterxml.jackson.annotation.JsonInclude;
import java.lang.Object;
import com.fasterxml.jackson.databind.annotation.JsonDeserialize;
import org.curriki.api.enus.java.LocalDateSerializer;

/**	
 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstClasse_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.AllWriter&fq=classeEtendGen_indexed_boolean:true">Find the class  in Solr. </a>
 * <br/>
 **/
public abstract class AllWriterGen<DEV> extends Object {
	protected static final Logger LOG = LoggerFactory.getLogger(AllWriter.class);

	//////////////////
	// siteRequest_ //
	//////////////////

	/**	 The entity siteRequest_
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected SiteRequestEnUS siteRequest_;
	@JsonIgnore
	public Wrap<SiteRequestEnUS> siteRequest_Wrap = new Wrap<SiteRequestEnUS>().var("siteRequest_").o(siteRequest_);

	/**	<br/> The entity siteRequest_
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.AllWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:siteRequest_">Find the entity siteRequest_ in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _siteRequest_(Wrap<SiteRequestEnUS> c);

	public SiteRequestEnUS getSiteRequest_() {
		return siteRequest_;
	}

	public void setSiteRequest_(SiteRequestEnUS siteRequest_) {
		this.siteRequest_ = siteRequest_;
		this.siteRequest_Wrap.alreadyInitialized = true;
	}
	public static SiteRequestEnUS staticSetSiteRequest_(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected AllWriter siteRequest_Init() {
		if(!siteRequest_Wrap.alreadyInitialized) {
			_siteRequest_(siteRequest_Wrap);
			if(siteRequest_ == null)
				setSiteRequest_(siteRequest_Wrap.o);
			siteRequest_Wrap.o(null);
		}
		siteRequest_Wrap.alreadyInitialized(true);
		return (AllWriter)this;
	}

	////////////
	// tabStr //
	////////////

	/**	 The entity tabStr
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected String tabStr;
	@JsonIgnore
	public Wrap<String> tabStrWrap = new Wrap<String>().var("tabStr").o(tabStr);

	/**	<br/> The entity tabStr
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.AllWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:tabStr">Find the entity tabStr in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _tabStr(Wrap<String> c);

	public String getTabStr() {
		return tabStr;
	}
	public void setTabStr(String o) {
		this.tabStr = AllWriter.staticSetTabStr(siteRequest_, o);
		this.tabStrWrap.alreadyInitialized = true;
	}
	public static String staticSetTabStr(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected AllWriter tabStrInit() {
		if(!tabStrWrap.alreadyInitialized) {
			_tabStr(tabStrWrap);
			if(tabStr == null)
				setTabStr(tabStrWrap.o);
			tabStrWrap.o(null);
		}
		tabStrWrap.alreadyInitialized(true);
		return (AllWriter)this;
	}

	public static String staticSolrTabStr(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrTabStr(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqTabStr(SiteRequestEnUS siteRequest_, String o) {
		return AllWriter.staticSolrStrTabStr(siteRequest_, AllWriter.staticSolrTabStr(siteRequest_, AllWriter.staticSetTabStr(siteRequest_, o)));
	}

	public String solrTabStr() {
		return AllWriter.staticSolrTabStr(siteRequest_, tabStr);
	}

	public String strTabStr() {
		return tabStr == null ? "" : tabStr;
	}

	public String sqlTabStr() {
		return tabStr;
	}

	public String jsonTabStr() {
		return tabStr == null ? "" : tabStr;
	}

	//////////
	// file //
	//////////

	/**	 The entity file
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected File file;
	@JsonIgnore
	public Wrap<File> fileWrap = new Wrap<File>().var("file").o(file);

	/**	<br/> The entity file
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.AllWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:file">Find the entity file in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _file(Wrap<File> c);

	public File getFile() {
		return file;
	}

	public void setFile(File file) {
		this.file = file;
		this.fileWrap.alreadyInitialized = true;
	}
	public static File staticSetFile(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected AllWriter fileInit() {
		if(!fileWrap.alreadyInitialized) {
			_file(fileWrap);
			if(file == null)
				setFile(fileWrap.o);
			fileWrap.o(null);
		}
		fileWrap.alreadyInitialized(true);
		return (AllWriter)this;
	}

	//////////////////
	// stringWriter //
	//////////////////

	/**	 The entity stringWriter
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected StringWriter stringWriter;
	@JsonIgnore
	public Wrap<StringWriter> stringWriterWrap = new Wrap<StringWriter>().var("stringWriter").o(stringWriter);

	/**	<br/> The entity stringWriter
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.AllWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:stringWriter">Find the entity stringWriter in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _stringWriter(Wrap<StringWriter> c);

	public StringWriter getStringWriter() {
		return stringWriter;
	}

	public void setStringWriter(StringWriter stringWriter) {
		this.stringWriter = stringWriter;
		this.stringWriterWrap.alreadyInitialized = true;
	}
	public static StringWriter staticSetStringWriter(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected AllWriter stringWriterInit() {
		if(!stringWriterWrap.alreadyInitialized) {
			_stringWriter(stringWriterWrap);
			if(stringWriter == null)
				setStringWriter(stringWriterWrap.o);
			stringWriterWrap.o(null);
		}
		stringWriterWrap.alreadyInitialized(true);
		return (AllWriter)this;
	}

	////////////
	// buffer //
	////////////

	/**	 The entity buffer
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected Buffer buffer;
	@JsonIgnore
	public Wrap<Buffer> bufferWrap = new Wrap<Buffer>().var("buffer").o(buffer);

	/**	<br/> The entity buffer
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.AllWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:buffer">Find the entity buffer in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _buffer(Wrap<Buffer> c);

	public Buffer getBuffer() {
		return buffer;
	}

	public void setBuffer(Buffer buffer) {
		this.buffer = buffer;
		this.bufferWrap.alreadyInitialized = true;
	}
	public static Buffer staticSetBuffer(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected AllWriter bufferInit() {
		if(!bufferWrap.alreadyInitialized) {
			_buffer(bufferWrap);
			if(buffer == null)
				setBuffer(bufferWrap.o);
			bufferWrap.o(null);
		}
		bufferWrap.alreadyInitialized(true);
		return (AllWriter)this;
	}

	/////////////////
	// printWriter //
	/////////////////

	/**	 The entity printWriter
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected PrintWriter printWriter;
	@JsonIgnore
	public Wrap<PrintWriter> printWriterWrap = new Wrap<PrintWriter>().var("printWriter").o(printWriter);

	/**	<br/> The entity printWriter
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.AllWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:printWriter">Find the entity printWriter in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _printWriter(Wrap<PrintWriter> c);

	public PrintWriter getPrintWriter() {
		return printWriter;
	}

	public void setPrintWriter(PrintWriter printWriter) {
		this.printWriter = printWriter;
		this.printWriterWrap.alreadyInitialized = true;
	}
	public static PrintWriter staticSetPrintWriter(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected AllWriter printWriterInit() {
		if(!printWriterWrap.alreadyInitialized) {
			_printWriter(printWriterWrap);
			if(printWriter == null)
				setPrintWriter(printWriterWrap.o);
			printWriterWrap.o(null);
		}
		printWriterWrap.alreadyInitialized(true);
		return (AllWriter)this;
	}

	///////////
	// empty //
	///////////

	/**	 The entity empty
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected Boolean empty;
	@JsonIgnore
	public Wrap<Boolean> emptyWrap = new Wrap<Boolean>().var("empty").o(empty);

	/**	<br/> The entity empty
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.AllWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:empty">Find the entity empty in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _empty(Wrap<Boolean> c);

	public Boolean getEmpty() {
		return empty;
	}

	public void setEmpty(Boolean empty) {
		this.empty = empty;
		this.emptyWrap.alreadyInitialized = true;
	}
	@JsonIgnore
	public void setEmpty(String o) {
		this.empty = AllWriter.staticSetEmpty(siteRequest_, o);
		this.emptyWrap.alreadyInitialized = true;
	}
	public static Boolean staticSetEmpty(SiteRequestEnUS siteRequest_, String o) {
		return Boolean.parseBoolean(o);
	}
	protected AllWriter emptyInit() {
		if(!emptyWrap.alreadyInitialized) {
			_empty(emptyWrap);
			if(empty == null)
				setEmpty(emptyWrap.o);
			emptyWrap.o(null);
		}
		emptyWrap.alreadyInitialized(true);
		return (AllWriter)this;
	}

	public static Boolean staticSolrEmpty(SiteRequestEnUS siteRequest_, Boolean o) {
		return o;
	}

	public static String staticSolrStrEmpty(SiteRequestEnUS siteRequest_, Boolean o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqEmpty(SiteRequestEnUS siteRequest_, String o) {
		return AllWriter.staticSolrStrEmpty(siteRequest_, AllWriter.staticSolrEmpty(siteRequest_, AllWriter.staticSetEmpty(siteRequest_, o)));
	}

	public Boolean solrEmpty() {
		return AllWriter.staticSolrEmpty(siteRequest_, empty);
	}

	public String strEmpty() {
		return empty == null ? "" : empty.toString();
	}

	public Boolean sqlEmpty() {
		return empty;
	}

	public String jsonEmpty() {
		return empty == null ? "" : empty.toString();
	}

	//////////////
	// initDeep //
	//////////////

	protected boolean alreadyInitializedAllWriter = false;

	public AllWriter initDeepAllWriter(SiteRequestEnUS siteRequest_) {
		setSiteRequest_(siteRequest_);
		if(!alreadyInitializedAllWriter) {
			alreadyInitializedAllWriter = true;
			initDeepAllWriter();
		}
		return (AllWriter)this;
	}

	public void initDeepAllWriter() {
		initAllWriter();
	}

	public void initAllWriter() {
				siteRequest_Init();
				tabStrInit();
				fileInit();
				stringWriterInit();
				bufferInit();
				printWriterInit();
				emptyInit();
	}

	public void initDeepForClass(SiteRequestEnUS siteRequest_) {
		initDeepAllWriter(siteRequest_);
	}

	/////////////////
	// siteRequest //
	/////////////////

	public void siteRequestAllWriter(SiteRequestEnUS siteRequest_) {
	}

	public void siteRequestForClass(SiteRequestEnUS siteRequest_) {
		siteRequestAllWriter(siteRequest_);
	}

	/////////////
	// obtain //
	/////////////

	public Object obtainForClass(String var) {
		String[] vars = StringUtils.split(var, ".");
		Object o = null;
		for(String v : vars) {
			if(o == null)
				o = obtainAllWriter(v);
			else if(o instanceof BaseModel) {
				BaseModel baseModel = (BaseModel)o;
				o = baseModel.obtainForClass(v);
			}
			else if(o instanceof Map) {
				Map<?, ?> map = (Map<?, ?>)o;
				o = map.get(v);
			}
		}
		return o;
	}
	public Object obtainAllWriter(String var) {
		AllWriter oAllWriter = (AllWriter)this;
		switch(var) {
			case "siteRequest_":
				return oAllWriter.siteRequest_;
			case "tabStr":
				return oAllWriter.tabStr;
			case "file":
				return oAllWriter.file;
			case "stringWriter":
				return oAllWriter.stringWriter;
			case "buffer":
				return oAllWriter.buffer;
			case "printWriter":
				return oAllWriter.printWriter;
			case "empty":
				return oAllWriter.empty;
			default:
				return null;
		}
	}

	///////////////
	// attribute //
	///////////////

	public boolean attributeForClass(String var, Object val) {
		String[] vars = StringUtils.split(var, ".");
		Object o = null;
		for(String v : vars) {
			if(o == null)
				o = attributeAllWriter(v, val);
			else if(o instanceof BaseModel) {
				BaseModel baseModel = (BaseModel)o;
				o = baseModel.attributeForClass(v, val);
			}
		}
		return o != null;
	}
	public Object attributeAllWriter(String var, Object val) {
		AllWriter oAllWriter = (AllWriter)this;
		switch(var) {
			default:
				return null;
		}
	}

	///////////////
	// staticSet //
	///////////////

	public static Object staticSetForClass(String entityVar, SiteRequestEnUS siteRequest_, String o) {
		return staticSetAllWriter(entityVar,  siteRequest_, o);
	}
	public static Object staticSetAllWriter(String entityVar, SiteRequestEnUS siteRequest_, String o) {
		switch(entityVar) {
		case "tabStr":
			return AllWriter.staticSetTabStr(siteRequest_, o);
		case "empty":
			return AllWriter.staticSetEmpty(siteRequest_, o);
			default:
				return null;
		}
	}

	////////////////
	// staticSolr //
	////////////////

	public static Object staticSolrForClass(String entityVar, SiteRequestEnUS siteRequest_, Object o) {
		return staticSolrAllWriter(entityVar,  siteRequest_, o);
	}
	public static Object staticSolrAllWriter(String entityVar, SiteRequestEnUS siteRequest_, Object o) {
		switch(entityVar) {
		case "tabStr":
			return AllWriter.staticSolrTabStr(siteRequest_, (String)o);
		case "empty":
			return AllWriter.staticSolrEmpty(siteRequest_, (Boolean)o);
			default:
				return null;
		}
	}

	///////////////////
	// staticSolrStr //
	///////////////////

	public static String staticSolrStrForClass(String entityVar, SiteRequestEnUS siteRequest_, Object o) {
		return staticSolrStrAllWriter(entityVar,  siteRequest_, o);
	}
	public static String staticSolrStrAllWriter(String entityVar, SiteRequestEnUS siteRequest_, Object o) {
		switch(entityVar) {
		case "tabStr":
			return AllWriter.staticSolrStrTabStr(siteRequest_, (String)o);
		case "empty":
			return AllWriter.staticSolrStrEmpty(siteRequest_, (Boolean)o);
			default:
				return null;
		}
	}

	//////////////////
	// staticSolrFq //
	//////////////////

	public static String staticSolrFqForClass(String entityVar, SiteRequestEnUS siteRequest_, String o) {
		return staticSolrFqAllWriter(entityVar,  siteRequest_, o);
	}
	public static String staticSolrFqAllWriter(String entityVar, SiteRequestEnUS siteRequest_, String o) {
		switch(entityVar) {
		case "tabStr":
			return AllWriter.staticSolrFqTabStr(siteRequest_, o);
		case "empty":
			return AllWriter.staticSolrFqEmpty(siteRequest_, o);
			default:
				return null;
		}
	}

	/////////////
	// define //
	/////////////

	public boolean defineForClass(String var, String val) {
		String[] vars = StringUtils.split(var, ".");
		Object o = null;
		if(val != null) {
			for(String v : vars) {
				if(o == null)
					o = defineAllWriter(v, val);
				else if(o instanceof BaseModel) {
					BaseModel oBaseModel = (BaseModel)o;
					o = oBaseModel.defineForClass(v, val);
				}
			}
		}
		return o != null;
	}
	public Object defineAllWriter(String var, String val) {
		switch(var.toLowerCase()) {
			default:
				return null;
		}
	}

	public boolean defineForClass(String var, Object val) {
		String[] vars = StringUtils.split(var, ".");
		Object o = null;
		if(val != null) {
			for(String v : vars) {
				if(o == null)
					o = defineAllWriter(v, val);
				else if(o instanceof BaseModel) {
					BaseModel oBaseModel = (BaseModel)o;
					o = oBaseModel.defineForClass(v, val);
				}
			}
		}
		return o != null;
	}
	public Object defineAllWriter(String var, Object val) {
		switch(var.toLowerCase()) {
			default:
				return null;
		}
	}

	//////////////////
	// apiRequest //
	//////////////////

	public void apiRequestAllWriter() {
		ApiRequest apiRequest = Optional.ofNullable(siteRequest_).map(SiteRequestEnUS::getApiRequest_).orElse(null);
		Object o = Optional.ofNullable(apiRequest).map(ApiRequest::getOriginal).orElse(null);
		if(o != null && o instanceof AllWriter) {
			AllWriter original = (AllWriter)o;
		}
	}

	//////////////
	// hashCode //
	//////////////

	@Override public int hashCode() {
		return Objects.hash();
	}

	////////////
	// equals //
	////////////

	@Override public boolean equals(Object o) {
		if(this == o)
			return true;
		if(!(o instanceof AllWriter))
			return false;
		AllWriter that = (AllWriter)o;
		return true;
	}

	//////////////
	// toString //
	//////////////

	@Override public String toString() {
		StringBuilder sb = new StringBuilder();
		sb.append("AllWriter { ");
		sb.append(" }");
		return sb.toString();
	}

	public static final String VAR_siteRequest_ = "siteRequest_";
	public static final String VAR_tabStr = "tabStr";
	public static final String VAR_file = "file";
	public static final String VAR_stringWriter = "stringWriter";
	public static final String VAR_buffer = "buffer";
	public static final String VAR_printWriter = "printWriter";
	public static final String VAR_empty = "empty";
}
