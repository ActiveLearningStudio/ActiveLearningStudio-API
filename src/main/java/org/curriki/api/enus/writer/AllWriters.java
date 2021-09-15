package org.curriki.api.enus.writer;

import java.io.IOException;
import java.util.List;

import org.curriki.api.enus.request.SiteRequestEnUS;
import org.curriki.api.enus.wrap.Wrap;

/**
 * Keyword: classSimpleNameAllWriters
 **/
public class AllWriters extends AllWritersGen<Object> {

	protected void _siteRequest_(Wrap<SiteRequestEnUS> c) {
	}

	public static AllWriters create(SiteRequestEnUS siteRequest_, AllWriter...writers) {
		AllWriters o = new AllWriters();
		o.initDeepForClass(siteRequest_);
		o.addWriters(writers);
		return o;
	}

	protected void _writers(List<AllWriter> c) {
	}

	public AllWriters t(int numberTabs, Object...objects) {
		for(AllWriter writer : writers) {
			writer.t(numberTabs, objects);
		}
		return this;
	}

	public AllWriters tl(int numberTabs, Object...objects) {
		for(AllWriter writer : writers) {
			writer.tl(numberTabs, objects);
		}
		return this;
	}

	public AllWriters l(Object...objects) {
		for(AllWriter writer : writers) {
			writer.l(objects);
		}
		return this;
	}

	public AllWriters s(Object...objects) { 
		for(AllWriter writer : writers) {
			writer.s(objects);
		}
		return this;
	}

	public void  flushClose() throws IOException, IOException {
		for(AllWriter writer : writers) {
			writer.flushClose();
		}
	}

	@Override()
	public String toString() {
		return writers.get(0).toString();
	}
}
