package org.curriki.api.enus.java;

import java.io.IOException;
import java.time.LocalDate;
import java.time.format.DateTimeFormatter;
import java.util.Locale;

import com.fasterxml.jackson.core.JsonGenerator;
import com.fasterxml.jackson.databind.JsonSerializer;
import com.fasterxml.jackson.databind.SerializerProvider;

/**
 * Keyword: classSimpleNameLocalDateSerializer
 * Map.hackathonMission: to create a new Java class LocalDateSerializer do convert a LocalDate to a String. 
 * Map.hackathonColumn: Develop Base Classes
 * Map.hackathonLabels: Java
 */
public class LocalDateSerializer extends JsonSerializer<LocalDate> {

	public static DateTimeFormatter FORMATDateDisplay = DateTimeFormatter.ofPattern("EEEE MMMM d yyyy", Locale.US);
	public static DateTimeFormatter FORMATDateSite = DateTimeFormatter.ofPattern("MMM dd, yyyy", Locale.US);

	@Override()
	public void  serialize(LocalDate o, JsonGenerator generator, SerializerProvider provider) throws IOException, IOException {
		generator.writeString(FORMATDateDisplay.format(o));
	}
}
