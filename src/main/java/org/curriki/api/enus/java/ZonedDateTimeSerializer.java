package org.curriki.api.enus.java;

import java.io.IOException;
import java.time.ZonedDateTime;
import java.time.format.DateTimeFormatter;
import java.util.Locale;

import com.fasterxml.jackson.core.JsonGenerator;
import com.fasterxml.jackson.databind.JsonSerializer;
import com.fasterxml.jackson.databind.SerializerProvider;

/**
 * Keyword: classSimpleNameZonedDateTimeSerializer
 * Map.hackathonMission: to create a new Java class ZonedDateTimeSerializer do convert a ZonedDateTime to a String. 
 * Map.hackathonColumn: Develop Base Classes
 * Map.hackathonLabels: Java
 */
public class ZonedDateTimeSerializer extends JsonSerializer<ZonedDateTime> {

	public static final String ZONED_DATE_TIME_FORMAT = "yyyy-MM-dd'T'HH:mm:ss.SSS'['VV']'";
	public static final DateTimeFormatter ZONED_DATE_TIME_FORMATTER = DateTimeFormatter.ofPattern(ZONED_DATE_TIME_FORMAT, Locale.US);

	@Override()
	public void  serialize(ZonedDateTime o, JsonGenerator generator, SerializerProvider provider) throws IOException, IOException {
		generator.writeString(o.format(ZONED_DATE_TIME_FORMATTER));
	}
}
