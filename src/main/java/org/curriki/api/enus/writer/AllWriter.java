package org.curriki.api.enus.writer;

import java.io.File;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.PrintWriter;
import java.io.StringWriter;
import java.util.List;

import org.apache.commons.lang3.StringUtils;
import org.apache.commons.lang3.exception.ExceptionUtils;
import org.apache.commons.text.WordUtils;

import org.curriki.api.enus.request.SiteRequestEnUS;
import org.curriki.api.enus.wrap.Wrap;

import io.vertx.core.buffer.Buffer;

/**
 * Keyword: classSimpleNameAllWriter
 **/
public class AllWriter extends AllWriterGen<Object> {

	protected void _siteRequest_(Wrap<SiteRequestEnUS> c) {
	}

	protected void _tabStr(Wrap<String> c) {
		c.o("\t");
	}

	public static AllWriter create(SiteRequestEnUS siteRequest_) {
		AllWriter o = new AllWriter();
		o.initDeepForClass(siteRequest_);
		return o;
	}

	public static AllWriter create(SiteRequestEnUS siteRequest_, String tabStr) {
		AllWriter o = new AllWriter();
		o.setTabStr(tabStr);
		o.initDeepForClass(siteRequest_);
		return o;
	}

	public static AllWriter create(SiteRequestEnUS siteRequest_, File file) {
		AllWriter o = new AllWriter();
		o.setFile(file);
		o.initDeepForClass(siteRequest_);
		return o;
	}

	public static AllWriter create(SiteRequestEnUS siteRequest_, File file, String tabStr) {
		AllWriter o = new AllWriter();
		o.setFile(file);
		o.setTabStr(tabStr);
		o.initDeepForClass(siteRequest_);
		return o;
	}

	public static AllWriter create(SiteRequestEnUS siteRequest_, Buffer buffer) {
		AllWriter o = new AllWriter();
		o.setBuffer(buffer);
		o.initDeepForClass(siteRequest_);
		return o;
	}

	public static AllWriter create(SiteRequestEnUS siteRequest_, Buffer buffer, String tabStr) {
		AllWriter o = new AllWriter();
		o.setBuffer(buffer);
		o.setTabStr(tabStr);
		o.initDeepForClass(siteRequest_);
		return o;
	}

	protected void _file(Wrap<File> c) {
	}

	protected void _stringWriter(Wrap<StringWriter> c) {
		if(file == null && buffer == null)
			c.o(new StringWriter());
	}

	protected void _buffer(Wrap<Buffer> c) {
	}

	protected void _printWriter(Wrap<PrintWriter> c) {
		if(buffer == null) {
			if(file == null)
				c.o(new PrintWriter(stringWriter));
			else {
				try {
					c.o(new PrintWriter(file));
				} catch (FileNotFoundException e) {
					ExceptionUtils.rethrow(e);
				}
			}
		}
	}

	protected void _empty(Wrap<Boolean> c) {
		c.o(true);
	}

	public AllWriter t(int tabNumber, Object...objects) {
		for(int i = 0; i < tabNumber; i++)
			s(tabStr);
		s(objects);
		return this;
	}

	public AllWriter tl(int tabNumber, Object...objects) {
		for(int i = 0; i < tabNumber; i++)
			s(tabStr);
		s(objects);
		s("\n");
		return this;
	}

	public AllWriter l(Object...objects) {
		s(objects);
		s("\n");
		return this;
	}

	public AllWriter s(Object...objects) { 
		for(Object object : objects) {
			if(object != null) {
				if(object instanceof List) {
					List<?> chain = (List<?>)object;
					for(Object object2 : chain) {
						String str = object2.toString();
						if(object2 != null && !StringUtils.isEmpty(str)) {
//							if(httpServerResponse == null)
							if(buffer == null)
								printWriter.write(str);
							else
//								httpServerResponse.write(str);
								buffer.appendString(str);
						}
					}
				}
				else {
					String str = object.toString();
					if(!StringUtils.isEmpty(str)) {
//						if(httpServerResponse == null)
						if(buffer == null)
							printWriter.write(str);
						else
//							httpServerResponse.write(str);
							buffer.appendString(str);
					}
				}
			}
		}
		empty = false;
		return this;
	}

	public AllWriter string(Object...objects) {
		s("\"");
		for(Object object : objects)
			if(object != null)
				s(StringUtils.replace(StringUtils.replace(object.toString(), "\\", "\\\\"), "\"", "\\\""));
		s("\"");
		return this;
	}

	public String q(Object...objects) {
		StringBuilder o = new StringBuilder();
		o.append("\"");
		for(Object object : objects)
			if(object != null)
				o.append(StringUtils.replace(StringUtils.replace(object.toString(), "\\", "\\\\"), "\"", "\\\""));
		o.append("\"");
		return o.toString();
	}

	public String qjs(Object...objects) {
		StringBuilder o = new StringBuilder();
		o.append("\"");
		for(Object object : objects)
			if(object != null)
				o.append(StringUtils.replace(StringUtils.replace(StringUtils.replace(object.toString(), "\\", "\\\\"), "\"", "\\\""), "\n", "\\n"));
		o.append("\"");
		return o.toString();
	}

	public String js(Object...objects) {
		StringBuilder o = new StringBuilder();
		for(Object object : objects)
			if(object != null)
				o.append(StringUtils.replace(StringUtils.replace(StringUtils.replace(object.toString(), "\\", "\\\\"), "\"", "\\\""), "\n", "\\n"));
		return o.toString();
	}

	public AllWriter yamlStr(int tabNumber, Object...objects) {
		StringWriter stringWriter = new StringWriter();
		PrintWriter printWriter = new PrintWriter(stringWriter);
		for(Object object : objects) {
			if(object != null) {
				if(object instanceof List) {
					List<?> chaine = (List<?>)object;
					for(Object object2 : chaine) {
						if(object2 != null && !StringUtils.isEmpty(object2.toString()))
							printWriter.append(object2.toString());
					}
				}
				else {
					if(!StringUtils.isEmpty(object.toString()))
						printWriter.append(object.toString());
				}
			}
		}
		String[] lines = StringUtils.splitPreserveAllTokens(stringWriter.toString(), "\n");
		l(">+");
		for(int i = 0; i < lines.length; i++) {
			boolean last = i == (lines.length -1);
			String line = lines[i];

			String[] wrapLines = StringUtils.splitPreserveAllTokens(WordUtils.wrap(line, 70), "\n");
			for(int j = 0; j < wrapLines.length; j++) {
				boolean wrapLast = j == (wrapLines.length -1);
				String wrapLine = wrapLines[j];
				if(wrapLast)
					t(tabNumber, wrapLine);
				else
					tl(tabNumber, wrapLine);
			}

			if(!last) {
				tl(tabNumber);
				if(StringUtils.isNotBlank(line))
					tl(tabNumber);
			}
			else {
				l();
			}
		}

		try {
			printWriter.flush();
			stringWriter.flush();
			printWriter.close();
			stringWriter.close();
		} catch (IOException e) {
			ExceptionUtils.rethrow(e);
		}
		return this;
	}

	public void  flushClose() {

		if(printWriter != null)
			printWriter.flush();
		if(stringWriter != null)
			stringWriter.flush();

		if(printWriter != null)
			printWriter.close();
		if(stringWriter != null) {
			try {
				stringWriter.close();
			} catch (IOException e) {
				ExceptionUtils.rethrow(e);
			}
		}
	}

	@Override()
	public String toString() {
		if(buffer != null)
			return stringWriter.toString();
		else if(file != null)
			return printWriter.toString();
		else
			return stringWriter.toString();
	}
}
