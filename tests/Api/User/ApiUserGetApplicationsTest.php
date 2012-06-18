<?php
/**
 * @author vl4dimir
 */
class ApiUserGetApplicationsTest extends AM_Test_PHPUnit_DatabaseTestCase
{
    protected function _getDataSetYmlFile()
    {
        return dirname(__FILE__)
                . DIRECTORY_SEPARATOR . '_fixtures'
                . DIRECTORY_SEPARATOR . 'ApiUserGetApplicationsTest.yml';
    }

    public function testShouldReturnListOfApplicationsForLoggedInUser()
    {
        //GIVEN
        date_default_timezone_set('Etc/GMT');
        Zend_Session::$_unitTestEnabled = true;

        $oStorageMock = $this->getMock('Zend_Auth_Storage_Session');
        Zend_Auth::getInstance()->setStorage($oStorageMock);

        $oExpectedUserObject = new stdClass();
        $oExpectedUserObject->first_name = 'John';
        $oExpectedUserObject->last_name  = 'Doe';
        $oExpectedUserObject->login      = 'john';
        $oExpectedUserObject->email      = 'john@mail.com';
        $oExpectedUserObject->id         = 1;
        $oExpectedUserObject->client     = 1;
        $oExpectedUserObject->is_admin   = 0;

        $oApiUser = new AM_Api_User();

        //THEN
        $oStorageMock->expects($this->any())
                ->method('read')
                ->will($this->returnValue($oExpectedUserObject));

        //WHEN
        $aResult = $oApiUser->getApplications('vp23rk326iem65udi5q38ob0o7');

        //THEN
        $aExpectedResult = array(
            'code'         => AM_Api_User::RESULT_SUCCESS,
            'applications' => array(
                1 => array(
                    'application_id'                       => 1,
                    'application_title'                    => 'Title',
                    'application_description'              => 'Description',
                    'application_product_id'               => 'com.padcms.application_1',
                    'application_notification_email'       => 'Email message',
                    'application_notification_email_title' => 'Email title',
                    'application_notification_twitter'     => 'Twitter message',
                    'application_notification_facebook'    => 'Facebook message',
                    'issues' => array (
                        1 => array(
                            'issue_id'              => 1,
                            'issue_title'           => 'Title',
                            'issue_number'          => 1,
                            'issue_state'           => 'work-in-progress',
                            'issue_product_id'      => 'com.padcms.issue_1',
                            'revisions' => array(
                                1 => array(
                                    'revision_id'               => 1,
                                    'revision_title'            => 'Title',
                                    'revision_state'            => 'work-in-progress',
                                    'revision_cover_image_list' => '/resources/export-cover-vertical/element/00/00/00/01/resource.png?',
                                    'revision_video'            => '/resources/none/element/00/00/00/02/resource.mp4',
                                    'revision_color'            => 'd9411a',
                                    'revision_horizontal_mode'  => '2pages',
                                    'revision_orientation'      => 'vertical',
                                    'help_pages'                => array(AM_Model_Db_IssueHelpPage::TYPE_HORIZONTAL => '/issue-help-page-horizontal/00/00/00/01/horizontal.png',
                                                                         AM_Model_Db_IssueHelpPage::TYPE_VERTICAL   => '/issue-help-page-vertical/00/00/00/01/vertical.png'),
                                    'revision_created'          => '2012-05-04T15:47:03+00:00'
                                )
                            )
                        )
                    )
                )
            )
        );

        $this->assertEquals($aExpectedResult, $aResult);
    }

    protected function tearDown()
    {
        parent::tearDown();
        Zend_Auth::getInstance()->setStorage(new Zend_Auth_Storage_Session());
    }
}