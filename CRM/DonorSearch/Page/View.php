<?php

class CRM_DonorSearch_Page_View extends CRM_Core_Page {

  public $useLivePageJS = TRUE;

  public function run() {
    $dao = new CRM_DonorSearch_DAO_SavedSearch();
    $headers = array(
      ts('Donor Name'),
      ts('Address'),
      ts('State'),
      ts('Donor\'s Spouse Name'),
      ts('Employer'),
      '',
    );
    $this->assign('headers', $headers);

    $donorSearches = array();
    $dao->find();
    while ($dao->fetch()) {
      $criteria = unserialize($dao->search_criteria);
      $donorSearches[$dao->id] = array(
        'IS' => CRM_Utils_System::url('civicrm/ds/integrated-search', "id=" . $dao->id),
        'delete' => CRM_Utils_System::url('civicrm/ds/delete', "id=" . $dao->id),
      );
      $donorSearches[$dao->id]['donor_name'] = sprintf('<a href=%s title="View DonorSearch details" class="action-item">%s %s %s</a>',
        CRM_DonorSearch_Util::getDonorSearchDetailsLink($criteria['id']),
        $criteria['dFname'],
        CRM_Utils_Array::value('dMname', $criteria, ''),
        $criteria['dLname']
      );
      $donorSearches[$dao->id]['address'] = sprintf('%s<br />%s',
        CRM_Utils_Array::value('dAddress', $criteria, ''),
        CRM_Utils_Array::value('dCity', $criteria, '')
      );
      $donorSearches[$dao->id]['state'] = $criteria['dState'];
      $donorSearches[$dao->id]['donor_spouse_name'] = sprintf('%s %s %s',
        CRM_Utils_Array::value('dSFname', $criteria, ''),
        CRM_Utils_Array::value('dSMname', $criteria, ''),
        CRM_Utils_Array::value('dSLname', $criteria, '')
      );
      $donorSearches[$dao->id]['employer'] = CRM_Utils_Array::value('dEmployer', $criteria, '');
    }
    $this->assign('rows', $donorSearches);

    parent::run();
  }

}
