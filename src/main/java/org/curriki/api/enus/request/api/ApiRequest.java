package org.curriki.api.enus.request.api; 

import java.time.Period;
import java.time.ZoneId;
import java.time.ZonedDateTime;
import java.time.temporal.ChronoUnit;
import java.util.List;
import java.util.Optional;
import java.util.UUID;

import org.curriki.api.enus.config.ConfigKeys;
import org.curriki.api.enus.request.SiteRequestEnUS;
import org.curriki.api.enus.wrap.Wrap;

/**
 * Keyword: classSimpleNameApiRequest
 */
public class ApiRequest extends ApiRequestGen<Object> {
	
	/**
	 * {@inheritDoc}
	 * Ignore: true
	 */
	protected void _siteRequest_(Wrap<SiteRequestEnUS> c) {}
	
	protected void _created(Wrap<ZonedDateTime> c) {
		c.o(ZonedDateTime.now(ZoneId.of(siteRequest_.getConfig().getString(ConfigKeys.SITE_ZONE))));
	}

	protected void _rows(Wrap<Integer> c) {
	}

	protected void _numFound(Wrap<Long> c) {
	}

	protected void _numPATCH(Wrap<Long> c) {
		c.o(0L);
	}

	protected void _uuid(Wrap<String> c) {
		c.o(UUID.randomUUID().toString());
	}

	protected void _id(Wrap<String> c) {
		c.o("PATCH-" + uuid);
	}

	protected void _pk(Wrap<Long> c) {
	}

	protected void _original(Wrap<Object> c) {
	}

	protected void _pks(List<Long> c) {
	}

	protected void _classes(List<String> c) {
	}

	protected void _vars(List<String> c) {
	}

	protected void _timeRemaining(Wrap<String> w) {
		w.o(calculateTimeRemaining());
	}

	public String calculateTimeRemaining() {
		ZonedDateTime now = ZonedDateTime.now(ZoneId.of(siteRequest_.getConfig().getString(ConfigKeys.SITE_ZONE)));
		Long timeDifferenceNow = ChronoUnit.SECONDS.between(created, now);
		Double ratio = ((double) numPATCH / numFound);
		Double remainingSeconds = ((double) timeDifferenceNow) / ratio - ((double) timeDifferenceNow);

		// Calculating the difference in Hours
		Long hours = ((Double) (remainingSeconds / 60 / 60)).longValue();

		// Calculating the difference in Minutes
		Long minutes = ((Double) ((remainingSeconds / 60) % 60)).longValue();

		// Calculating the difference in Seconds
		Long seconds = ((Double) (remainingSeconds % 60)).longValue();

		return (hours > 0L ? hours + " hours " : "") + (minutes > 0L ? minutes + " minutes " : "") + (hours == 0L && minutes <= 1L ? seconds + " seconds." : "");
	}
}