package org.curriki.api.enus.vertx;

import static org.apache.commons.lang3.Validate.isTrue;

import java.io.IOException;
import java.math.BigDecimal;
import java.math.RoundingMode;
import java.text.DecimalFormat;
import java.text.DecimalFormatSymbols;
import java.text.NumberFormat;
import java.time.ZoneId;
import java.time.ZonedDateTime;
import java.time.format.DateTimeFormatter;
import java.util.Locale;
import java.util.TimeZone;

import org.curriki.api.enus.java.ZonedDateTimeDeserializer;
import org.curriki.api.enus.java.ZonedDateTimeSerializer;

import com.github.jknack.handlebars.Helper;
import com.github.jknack.handlebars.Options;
import com.github.jknack.handlebars.internal.lang3.LocaleUtils;

public enum SiteHelpers implements Helper<Object> {


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
	},

	/**
	 * <p>
	 * Usage:
	 * </p>
	 *
	 * <pre>
	 *		{{numberFormat number ["format"] [locale=default]}}
	 * </pre>
	 *
	 * Format parameters is one of:
	 * <ul>
	 * <li>"integer": the integer number format</li>
	 * <li>"percent": the percent number format</li>
	 * <li>"currency": the decimal number format</li>
	 * <li>"pattern": a decimal pattern.</li>
	 * </ul>
	 * Otherwise, the default formatter will be used.
	 *
	 * <p>
	 * More options:
	 * </p>
	 * <ul>
	 * <li>groupingUsed: Set whether or not grouping will be used in this format.</li>
	 * <li>maximumFractionDigits: Sets the maximum number of digits allowed in the fraction portion of
	 * a number.</li>
	 * <li>maximumIntegerDigits: Sets the maximum number of digits allowed in the integer portion of a
	 * number</li>
	 * <li>minimumFractionDigits: Sets the minimum number of digits allowed in the fraction portion of
	 * a number</li>
	 * <li>minimumIntegerDigits: Sets the minimum number of digits allowed in the integer portion of a
	 * number.</li>
	 * <li>parseIntegerOnly: Sets whether or not numbers should be parsed as integers only.</li>
	 * <li>roundingMode: Sets the {@link java.math.RoundingMode} used in this NumberFormat.</li>
	 * </ul>
	 *
	 * @see NumberFormat
	 * @see DecimalFormat
	 */
	siteNumberFormat {

		@Override
		public Object apply(final Object context, final Options options) throws IOException {
			if (context instanceof Number) {
				return safeApply(context, options);
			} else {
				return safeApply(new BigDecimal(context.toString()), options);
			}
		}

		protected CharSequence safeApply(final Object value, final Options options) {
			isTrue(value instanceof Number, "found '%s', expected 'number'", value);
			Number number = (Number) value;
			final NumberFormat numberFormat = build(options);

			Boolean groupingUsed = options.hash("groupingUsed");
			if (groupingUsed != null) {
				numberFormat.setGroupingUsed(groupingUsed);
			}

			Integer maximumFractionDigits = options.hash("maximumFractionDigits");
			if (maximumFractionDigits != null) {
				numberFormat.setMaximumFractionDigits(maximumFractionDigits);
			}

			Integer maximumIntegerDigits = options.hash("maximumIntegerDigits");
			if (maximumIntegerDigits != null) {
				numberFormat.setMaximumIntegerDigits(maximumIntegerDigits);
			}

			Integer minimumFractionDigits = options.hash("minimumFractionDigits");
			if (minimumFractionDigits != null) {
				numberFormat.setMinimumFractionDigits(minimumFractionDigits);
			}

			Integer minimumIntegerDigits = options.hash("minimumIntegerDigits");
			if (minimumIntegerDigits != null) {
				numberFormat.setMinimumIntegerDigits(minimumIntegerDigits);
			}

			Boolean parseIntegerOnly = options.hash("parseIntegerOnly");
			if (parseIntegerOnly != null) {
				numberFormat.setParseIntegerOnly(parseIntegerOnly);
			}

			String roundingMode = options.hash("roundingMode");
			if (roundingMode != null) {
				numberFormat.setRoundingMode(RoundingMode.valueOf(roundingMode.toUpperCase().trim()));
			}

			return numberFormat.format(number);
		}

		/**
		 * Build a number format from options.
		 *
		 * @param options The helper options.
		 * @return The number format to use.
		 */
		private NumberFormat build(final Options options) {
			if (options.params.length == 0) {
				return NumberStyle.DEFAULT.numberFormat(Locale.getDefault());
			}
			isTrue(options.params[0] instanceof String, "found '%s', expected 'string'",
					options.params[0]);
			String format = options.param(0);
			String localeStr = options.param(1, Locale.getDefault().toString());
			Locale locale = LocaleUtils.toLocale(localeStr);
			try {
				NumberStyle style = NumberStyle.valueOf(format.toUpperCase().trim());
				return style.numberFormat(locale);
			} catch (ArrayIndexOutOfBoundsException ex) {
				return NumberStyle.DEFAULT.numberFormat(locale);
			} catch (IllegalArgumentException ex) {
				return new DecimalFormat(format, new DecimalFormatSymbols(locale));
			}
		}

	},

	/**
	 * <p>
	 * Usage:
	 * </p>
	 *
	 * <pre>
	 *		{{dateFormat date ["format"] [format="format"][tz=timeZone|timeZoneId]}}
	 * </pre>
	 *
	 * Format parameters is one of:
	 * <ul>
	 * <li>"full": full date format. For example: Tuesday, June 19, 2012</li>
	 * <li>"long": long date format. For example: June 19, 2012</li>
	 * <li>"medium": medium date format. For example: Jun 19, 2012</li>
	 * <li>"short": short date format. For example: 6/19/12</li>
	 * <li>"pattern": a date pattern.</li>
	 * </ul>
	 * Otherwise, the default formatter will be used.
	 * The format option can be specified as a parameter or hash (a.k.a named parameter).
	 */
	siteZonedDateTimeFormat {

		public Object apply(final Object context, final Options options) throws IOException {
			if (options.isFalsy(context)) {
				Object param = options.param(0, null);
				return param == null ? null : param.toString();
			}
			return safeApply(context, options);
		}

		protected CharSequence safeApply(final Object value, final Options options) {
			final DateTimeFormatter dateFormatter;
			Object pattern = options.param(0, options.hash("format", "yyyy-MM-dd'T'HH:mm:ss.SSS'['VV']'"));
			String localeStr = options.param(1, options.hash("locale", Locale.getDefault().toString()));
			Locale locale = LocaleUtils.toLocale(localeStr);
			dateFormatter = DateTimeFormatter.ofPattern(pattern.toString(), locale);
			Object tz = options.hash("tz");
			if (tz != null) {
				final TimeZone timeZone = tz instanceof TimeZone ? (TimeZone) tz : TimeZone.getTimeZone(tz.toString());
				dateFormatter.withZone(timeZone.toZoneId());
			}
			ZonedDateTime date = value instanceof ZonedDateTime ? (ZonedDateTime)value : ZonedDateTime.parse(value.toString(), DateTimeFormatter.ofPattern(ZonedDateTimeDeserializer.ZONED_DATE_TIME_FORMAT, locale));
			return dateFormatter.format(date);
		}

	},
	;
}

/**
 * Number format styles.
 *
 * @author edgar.espina
 * @since 1.0.1
 */
enum NumberStyle {

	/**
	 * The default number format.
	 */
	DEFAULT {
	 @Override
		public NumberFormat numberFormat(final Locale locale) {
			return NumberFormat.getInstance(locale);
		}
	},

	/**
	 * The integer number format.
	 */
	INTEGER {
		@Override
		public NumberFormat numberFormat(final Locale locale) {
			return NumberFormat.getIntegerInstance(locale);
		}
	},

	/**
	 * The currency number format.
	 */
	CURRENCY {
		@Override
		public NumberFormat numberFormat(final Locale locale) {
			return NumberFormat.getCurrencyInstance(locale);
		}
	},

	/**
	 * The percent number format.
	 */
	PERCENT {
		@Override
		public NumberFormat numberFormat(final Locale locale) {
			return NumberFormat.getPercentInstance(locale);
		}
	};

	/**
	 * Build a new number format.
	 *
	 * @param locale The locale to use.
	 * @return A new number format.
	 */
	public abstract NumberFormat numberFormat(Locale locale);
}
