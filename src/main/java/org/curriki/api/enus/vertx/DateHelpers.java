package org.curriki.api.enus.vertx;

import java.io.IOException;
import java.time.ZoneId;
import java.time.ZonedDateTime;
import java.time.format.DateTimeFormatter;
import java.util.Locale;

import org.curriki.api.enus.java.ZonedDateTimeSerializer;

import com.github.jknack.handlebars.Helper;
import com.github.jknack.handlebars.Options;

/**
 * Map.hackathonMission: to create a new Java enum to handle date formatting in the frontend page requests
 * Map.hackathonColumn: Develop Web Backend
 * Map.hackathonLabels: Java,Handlebars
 */
public enum DateHelpers implements Helper<Object> {

	/**
	 */
	formatZonedDateTime {
		@Override
		public CharSequence apply(final Object a, final Options options) throws IOException {
			String pattern = options.param(0);
			String localeId = options.param(1);
			Locale locale = Locale.forLanguageTag(localeId);
			String zone = options.param(2);
			ZoneId zoneId = ZoneId.of(zone);
			String str = (String)a;
			ZonedDateTime d = ZonedDateTime.parse(str, ZonedDateTimeSerializer.ZONED_DATE_TIME_FORMATTER);
			return DateTimeFormatter.ofPattern(pattern, locale).format(d.withZoneSameInstant(zoneId));
		}
	}
	;
}
