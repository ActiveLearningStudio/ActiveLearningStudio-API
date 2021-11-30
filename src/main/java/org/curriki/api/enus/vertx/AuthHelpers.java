package org.curriki.api.enus.vertx;

import java.io.IOException;
import java.util.Arrays;
import java.util.List;
import java.util.Objects;
import java.util.Optional;

import org.apache.commons.collections.CollectionUtils;

import com.github.jknack.handlebars.Helper;
import com.github.jknack.handlebars.Options;
import com.github.jknack.handlebars.TagType;

/**
 * Map.hackathonMission: to create a new Java enum to handle authentication in the frontend page requests
 * Map.hackathonColumn: Develop Web Backend
 * Map.hackathonLabels: Java,Handlebars
 */
public enum AuthHelpers implements Helper<Object> {

	/**
	 */
	ifContainsKeysAnyRolesOrSessionId {
		@Override
		public Object apply(final Object a, final Options options) throws IOException {
			List<Long> expectedUserKeys = options.param(0, null);
			Long userKey = options.param(1, null);
			String expectedSessionId = options.param(2, null);
			String sessionId = options.param(3, null);
			List<String> userRoles = options.param(4, null);
			List<String> requiredRoles = Optional.ofNullable(options.param(5, null)).map(o -> o instanceof List ? (List<String>)o : Arrays.asList(o.toString())).orElse(Arrays.asList());

			Boolean result = expectedUserKeys.contains(userKey) 
					|| Objects.equals(sessionId, expectedSessionId)
					|| CollectionUtils.containsAny(userRoles, requiredRoles)
					;
			if (options.tagType == TagType.SECTION) {
				return result ? options.fn() : options.inverse();
			}
			return result ? options.hash("yes", true) : options.hash("no", false);
		}
	},

	/**
	 */
	ifContainsAnyRoles {
		@Override
		public Object apply(final Object a, final Options options) throws IOException {
			List<String> userRoles = Optional.ofNullable(a).map(o -> o instanceof List ? (List<String>)o : Arrays.asList(o.toString())).orElse(Arrays.asList());
			List<String> requiredRoles = Optional.ofNullable(options.param(0, null)).map(o -> o instanceof List ? (List<String>)o : Arrays.asList(o.toString())).orElse(Arrays.asList());

			Boolean result = CollectionUtils.containsAny(userRoles, requiredRoles)
					;
			if (options.tagType == TagType.SECTION) {
				return result ? options.fn() : options.inverse();
			}
			return result ? options.hash("yes", true) : options.hash("no", false);
		}
	},

	/**
	 */
	ifContainsKeys {
		@Override
		public Object apply(final Object a, final Options options) throws IOException {
			List<Long> expectedUserKeys = options.param(0, null);
			Long userKey = options.param(1, null);

			Boolean result = expectedUserKeys.contains(userKey) 
					;
			if (options.tagType == TagType.SECTION) {
				return result ? options.fn() : options.inverse();
			}
			return result ? options.hash("yes", true) : options.hash("no", false);
		}
	},

	/**
	 */
	ifContainsSessionId {
		@Override
		public Object apply(final Object a, final Options options) throws IOException {
			String expectedSessionId = options.param(0, null);
			String sessionId = options.param(1, null);

			Boolean result = Objects.equals(sessionId, expectedSessionId)
					;
			if (options.tagType == TagType.SECTION) {
				return result ? options.fn() : options.inverse();
			}
			return result ? options.hash("yes", true) : options.hash("no", false);
		}
	}
	;
}
