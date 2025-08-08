// @ts-check
class Utils {
    /**
     * Calculate age with DOB in `YYYY-MM-DD` format
     * @param {string} birthDateString 
     * @returns {{years: number, months: number, days: number}} age in exact details
     */
    static calculateExactAge(birthDateString) {
        const birthDate = new Date(birthDateString);
        const currentDate = new Date();
        let yearsDifference = currentDate.getFullYear() - birthDate.getFullYear();
        let monthsDifference = currentDate.getMonth() - birthDate.getMonth();
        let daysDifference = currentDate.getDate() - birthDate.getDate();

        if (daysDifference < 0) {
            const previousMonthEndDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), 0);
            daysDifference += previousMonthEndDate.getDate();
            monthsDifference--;
        }

        if (monthsDifference < 0) {
            monthsDifference += 12;
            yearsDifference--;
        }

        return {
            years: yearsDifference,
            months: monthsDifference,
            days: daysDifference
        };
    }

    /**
     * Format angka menjadi mata uang rupiah
     * @param {number} amount 
     * @returns {string}
     */
    static rp(amount) {
        const formattedAmount = 'Rp ' + amount.toLocaleString('id-ID');
        return formattedAmount;
    }
    
    /**
     * Enforce number input min max limit on manual input
     * @param {Event} event 
     */
    static enforceNumberLimit(event) {
        const inputField = /** @type {HTMLInputElement} */ (event.target);
        let value = parseFloat(inputField.value);
        let min = parseInt(String(inputField.min || 0));
        let max = parseInt(String(inputField.max || Number.MAX_SAFE_INTEGER));

        if (isNaN(value)) {
            inputField.value = '';
            return;
        }

        if (value < min) {
            inputField.value = String(min);
        } else if (value > max) {
            inputField.value = String(max);
        }
    }
}