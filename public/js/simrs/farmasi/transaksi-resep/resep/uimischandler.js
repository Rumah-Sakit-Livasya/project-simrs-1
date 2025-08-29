// @ts-check
/// <reference types="jquery" />
/// <reference types="select2" />
/// <reference path="../../../../types.d.ts" />

/**
 * A class for miscellaneous UI handling logic that doesn't involve
 * direct element updates or HTML string generation.
 */
class UIMiscHandler {
    /**
     * Custom matcher for the Select2 drug dropdown to allow searching
     * by drug name or active substance (zat aktif).
     * @param {import("select2").SearchOptions} params
     * @param {import("select2").OptGroupData | import("select2").OptionData} data
     * @returns {import("select2").OptGroupData | import("select2").OptionData | null}
     */
    obatMatcher(params, data) {
        if ($.trim(params.term) === '') {
            return data;
        }

        const zatCheck = $("#zat_aktif");
        const term = params.term.toLowerCase();
        const text = data.text.toLowerCase();
        const $el = $(data.element);
        const zat = $el.data('zat')?.toString().toLowerCase();

        if (zatCheck.is(':checked')) {
            if (zat && zat.includes(term)) {
                return data;
            }
        } else {
            if (text.includes(term)) {
                return data;
            }
        }

        return null;
    }
}