package org.curriki.api.enus.wrap;

import java.io.Serializable;
import java.util.ArrayList;

/**
 * Keyword: classSimpleNameWrap
 **/
public class Wrap<DEV> implements Serializable {

	public String var;

	public Wrap<DEV> var(String o) {
		var = o;
		return this;
	}

	public DEV o;

	public Wrap<DEV> o(DEV o) {
		this.o = o;
		return this;
	}

	public Boolean alreadyInitialized = false;

	public Wrap<DEV> alreadyInitialized(Boolean o) {
		alreadyInitialized = o;
		return this;
	}
}
