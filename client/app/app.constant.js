'use strict';

aadinathUI.constant('CONSUMABLE', {
	dropdown: [
		{value: 'Cash', name: 'Cash'},
		{value: 'Consumable', name: 'Consumable'},
		{value: 'Jig', name: 'Jig'},
		{value: 'Stationary', name: 'Stationary'},
		{value: 'SpareParts', name: 'Spare Parts'},
		{value: 'UtilityParts', name: 'Utility Spare Parts'},
	]
});

aadinathUI.constant('SPAREPARTS_CATEGORIES', [
	{value: 'IM', name: 'IM'},
	{value: 'OM', name: 'OM'},
	{value: 'UV', name: 'UV'},
	{value: 'Office', name: 'Office'},
	{value: 'Utility', name: 'Utility'}
]);

aadinathUI.constant('API_URL', 'server/public/api/V1/');
aadinathUI.constant('QUOTATION_FILE_PATH', '');
aadinathUI.constant('LEADSTATUS', [
		{value: 'new', name: 'New', type: '1'},
		{value: 'sampling_requested', name: 'Sampling Requested', type: '2'},
		{value: 'sampling_underprocess', name: 'Sampling Underprocess', type: '2'},
		{value: 'sampling_approved', name: 'Sampling Approved', type: '2'},
		{value: 'sampling_rejected', name: 'Sampling Rejected', type: '2'},
		{value: 'po_requested', name: 'PO Requested', type: '3'},
		{value: 'po_received', name: 'PO Received', type: '3'},
		{value: 'po_accepted', name: 'PO Accepted', type: '3'},
		{value: 'pi_sent', name: 'PI Sent', type: '4'}
	]
);
aadinathUI.constant('DURATION', {
	dropdown: [
		{value: 'Daily', name: 'Daily'},
		{value: 'Weekly', name: 'Weekly'},
		{value: 'BiWeekly', name: 'BiWeekly'},
		{value: 'Monthly', name: 'Monthly'}
	]
});

aadinathUI.constant('INDUSTRY_TYPE', [
		{value: 'cosmetic', name: 'Cosmetic'},
		{value: 'electrical', name: 'Electrical'},
		{value: 'perfume', name: 'Perfume'},
		{value: 'lighting', name: 'Lighting'},
		{value: 'automobile', name: 'Automobile'},
		{value: 'footwear', name: 'Footwear'},
		{value: 'home_appliances', name: 'Home Appliances'},
		{value: 'others', name: 'Others'},
	]
);

aadinathUI.constant('drumsize', {
	basecoat:18,
	topcoat:18,
	primier:15
});

aadinathUI.constant('DAYS', [
		{value: '1', name: 'Monday'},
		{value: '2', name: 'Tuesday'},
		{value: '3', name: 'Wednesday'},
		{value: '4', name: 'Thrusday'},
		{value: '5', name: 'Friday'},
		{value: '6', name: 'Saturday'},
		{value: '7', name: 'Sunday'},
	]
);

aadinathUI.constant('EMP_DOCUMENT_TYPE', [
		{value: 'photo', name: 'Photo'},
		{value: 'aadhar', name: 'Aadhar Card'},
		{value: 'id_card', name: 'Any other ID Card'},
		{value: 'ssc', name: 'Secondary'},
		{value: 'hssc', name: 'Higher Secondary'},
		{value: 'iti', name: 'ITI'},
		{value: 'graduation', name: 'Graduation'},
		{value: 'pg', name: 'Post Graduation'}
	]
);

aadinathUI.constant('MANPOWER', [
		{value: 'conveyor', name: 'conveyor', required: '12'},
		{value: 'metallizer', name: 'metallizer', required: '2'},
		{value: 'paintGun', name: 'paint gun operation', required: '2'},
		{value: 'finalQualityChecker', name: 'final quality checking', required: '1'},
		{value: 'lineInspection', name: 'line inspection', required: '3'},
		{value: 'fixtureAssembly', name: 'Fixture assembly', required: '2'},
		{value: 'packing', name: 'Packing', required: '0'},
		{value: 'cleaning', name: 'Cleaning', required: '1'},
		{value: 'supervisor', name: 'Supervisor', required: '1'},
		{value: 'extraDuty', name: 'Extra Duty', required: '0'},
	]
);

aadinathUI.constant('ATTENDANCE_DEVICE', [
		{value: '1', name: 'Day'},
		{value: '4', name: 'Night'}
	]
);
