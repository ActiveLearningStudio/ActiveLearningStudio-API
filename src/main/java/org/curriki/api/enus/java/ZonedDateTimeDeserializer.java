package org.curriki.api.enus.java;

import java.io.IOException;
import java.time.ZonedDateTime;
import java.time.format.DateTimeFormatter;

import com.fasterxml.jackson.core.JsonParser;
import com.fasterxml.jackson.databind.DeserializationContext;
import com.fasterxml.jackson.databind.JsonDeserializer;

/**
 * Keyword: classSimpleNameZonedDateTimeDeserializer
 * Map.hackathonMission: to create a new Java class ZonedDateTimeDeserializer do convert a String to a ZonedDateTime. 
 * Map.hackathonColumn: Develop Base Classes
 * Map.hackathonLabels: Java
 */
public class ZonedDateTimeDeserializer extends JsonDeserializer<ZonedDateTime> {

	public static final String ZONED_DATE_TIME_FORMAT = "yyyy-MM-dd'T'HH:mm:ss.SSS'['VV']'";

	public static final DateTimeFormatter ZONED_DATE_TIME_FORMATTER = DateTimeFormatter.ofPattern(ZONED_DATE_TIME_FORMAT);

	@Override
	public ZonedDateTime deserialize(JsonParser jsonParser, DeserializationContext deserializationContext)
			throws IOException {

		return ZonedDateTime.parse(jsonParser.getText(), DateTimeFormatter.ofPattern(ZONED_DATE_TIME_FORMAT, deserializationContext.getLocale()).withZone(deserializationContext.getTimeZone().toZoneId()));
	}
}
