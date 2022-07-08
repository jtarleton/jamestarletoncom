<?php

namespace Drupal\cb_project_portfolio\Controller;

use Drupal\Core\Controller\ControllerBase;

use Symfony\Component\HttpFoundation\JsonResponse;
/**
 * Returns responses for cb_project_portfolio routes.
 */
class CbProjectPortfolioController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It gworks!'),
    ];

    return $build;
  }



  public function json() {
  /*
	  $json = '{
  "basics": {
    "name": "James Tarleton",
    "label": "Software Engineer",
    "image": "https://media-exp1.licdn.com/dms/image/C5603AQHr5XkpgJUrbg/profile-displayphoto-shrink_400_400/0/1602782122008?e=1645660800&v=beta&t=QOkhqu1GCIUlnVgoFmrGa6rt6vw_z87EYRUSvKgvI2A",
    "email": "jamestarleton@gmail.com",
    "phone": "(646) 203-7629",
    "url": "https://www.jamestarleton.com",
    "summary": "Experienced full-stack developer with proven technical leadership ability focused on server-side programming with experience developing high-traffic web applications in modern cloud environments.",
    "location": {
      "address": "171 E 83rd St, Apt 5A",
      "postalCode": "NY 10028",
      "city": "New York",
      "countryCode": "US",
      "region": "New York"
    },
    "profiles": [{
      "network": "LinkedIn",
      "username": "jtarleton",
      "url": "https://www.linkedin.com/in/jtarleton/"
    },
    {
      "network": "GitHub",
      "username": "jtarleton",
      "url": "https://github.com/jtarleton"
    },
    {
      "network": "StackOverflow",
      "username": "jtarleton",
      "url": "https://stackoverflow.com/users/1172735/jtarleton"
    }]
  },


 "work": [

{
    "name": "Velir, LLC",
    "position": "Senior Drupal Developer",
    "url": "https://www.velir.com",
    "startDate": "2022-01-10",
    "endDate": "2099-01-01",
    "summary": "Senior Drupal Developer",
    "highlights": [
      "• Develop and test mission-critical, customer-facing web applications (Drupal 8), external API integrations.", 
      "• Coordinate with business analysts, security, architects, and project managers on use cases and planning of application features at all phases of the SDLC.", 
      "• Perform security assessments and remediations to improve DevSecOps processes and deploy timely Drupal core and module upgrades/patches.", 
      "• Develop for accessibility and WCAG compliance." 
    ]
  },
    {
    "name": "City of New York, Department of Transportation (DOT) IT&Telecom ",
    "position": "Senior Software Engineer",
    "url": "https://www1.nyc.gov/html/dot/html/home/home.shtml",
    "startDate": "2018-11-21",
    "endDate": "2022-01-07",
    "summary": "Senior Application Developer / ITCS Consultant",
    "highlights": [
      "• Develop and test mission-critical, customer-facing web applications (Drupal 8): DOT Projects & Initiatives, Authorized Parking Application (APA), Street Improvements, and other official DOT websites, including user and identity management, Active Directory integration, forms and reports, interactive feedback maps, geolocation, and external API integrations.", 
      "• Coordinate with business analysts, security, architects, and project managers on use cases and planning of    application features at all phases of the SDLC.", 
      "• Migrate legacy parking permits (DB2 to MySQL) through custom Drupal module and ETL process development.",  
      "• Plan and develop internal reporting applications for tracking customer activity, KPIs, and performance metrics.", 
      "• Mentor and collaborate with other developers offering technical guidance and directing development tasks.",  
      "• Re-design existing features to meet system requirement specifications and business needs.", 
      "• Design exciting user experiences to implement business stakeholders’ visions.", 
      "• Perform security assessments and remediations to improve DevSecOps processes and deploy timely Drupal core and module upgrades/patches.", 
      "• Develop for accessibility and WCAG compliance." 
    ]
  },

{
    "name": "Association of National Advertisers (ANA) - Advertising Digital Identification, LLC",
    "position": "Application Developer",
    "url": "https://www.ad-id.org",
    "startDate": "2013-01-01",
    "endDate": "2018-10-31",
    "summary": "Application Developer",
    "highlights": [
      "• Developed web application features and Drupal modules for quarterly feature releases on the Ad-ID (4A\'s/Association of National Advertisers) digital advertising asset tracking and identification system.",  
      "• Designed global site search engine (Lucene/Solr) features and Solr/PHP integrations, including writing SQL queries, tuning Lucene DisMax queries, data import handler configurations, schemas, and automating processes for indexing MySQL.", 
      "• Wrote Solr schema, analyzer, tokenizer, and filter configurations, field mappings and custom fields, request    handlers, suggester, and highlighting components, and JVM configurations.", 
      "• Analyzed customer and business needs and translate application requirements into technical specifications.", 
      "• Adapted business rules to data models, business objects, and application logic.", 
      "• Developed internal data warehousing database and reporting applications for tracking key business   performance metrics (KPIs) and forecasting sales.", 
      "• Developed custom ETL processes for nightly data migrations measuring customer activity and churn. Build   custom applications to automate business processes for data validation reports provided to media vendors.", 
      "• Wrote automated functional and unit tests (SimpleTest framework)." 

    ]
  },

{
    "name": "Association of National Advertisers (ANA)",
    "position": "Application Developer",
    "url": "https://www.ana.net",
    "startDate": "2011-03-01",
    "endDate": "2013-12-31",
    "summary": "Application Developer",
    "highlights": [
      "• Developed modules for internal business applications within a PHP framework (symfony).",  
      "• Led migration of proprietary legacy applications and databases (MySQL) for ANA subsidiary organization (Ad  ID) to MySQL/Drupal 7.",  
      "• Led migration of legacy applications and databases (DBText) for internal department (Marketing Knowledge Center) to symfony 1.4/MongoDB.",  
      "• Scrubbed data from legacy systems to improve data quality and correct database anomalies.",  
      "• Built custom user authentication, roles, and privileges system with MS Active Directory integration.", 
      "• Wrote unit and functional tests (Lime framework)."
    ]
  },
{
    "name": "Barnes & Noble, Inc. – SparkNotes Team",
    "position": "Web Developer",
    "url": "https://www.sparknotes.com",
    "startDate": "2008-07-01",
    "endDate": "2011-02-28",
    "summary": "Web Developer",
    "highlights": [
      "• Developed highly scalable web applications within a PHP framework (symfony).", 
      "• Delivered continuous site enhancements, blogging applications, and content management features.",  
      "• Integrated front-end with highly customized Wordpress implementation to support a major front-end redesign and site re-launch.", 
      "• Led design of SparkLife module including collaborative features for bloggers and editors.",
      "• Developed front-end and back-end for new SparkCollege symfony module allowing prep students to search for  colleges/universities (Solr).",
      "• Integrated SparkCollege module with MySQL, generating Lucene queries from request data and parsing Solr response data, including geospatial parameters (e.g. within a mile/km radius, sorting colleges by distance from a zip code) and an auto-complete feature.", 
      "• Created MySQL databases and APIs using the Propel ORM library.",
      "• Integrated advertising code, social networking features, and third-party APIs.",
      "• Wrote automated functional and unit tests (Lime framework)."
    ]
  },
{
    "name": "Phoenix Recycling, Inc.",
    "position": "Web Developer",
    "url": "https://www.sparknotes.com",
    "startDate": "2007-08-01",
    "endDate": "2008-06-30",
    "summary": "Web Developer",
    "highlights": [
      "• Designed company database (MySQL).", 
      "• Developed database-driven web application for small business.",  
      "• Delivered features including online customer sign -up, automated billing, customer account tracking, order and route information tracking, and Google Maps API (JavaScript) integration.",  
      "• Grew customer base from approximately 500 to several thousand within three months of initial site launch.",
      "• Wrote automated functional and unit tests (Lime framework)."
    ]
  },

{
    "name": "Auburn University, Dept. of Athletics",
    "position": "Computing Lab Administrator",
    "url": "https://auburntigers.com/sports/2019/3/12/academics-index-html.aspx",
    "startDate": "2005-07-01",
    "endDate": "2008-06-30",
    "summary": "Computing Lab Administrator",
    "highlights": [
      "• Provided general IT help desk services for Office of Academic Services.",  
      "• Provided technical support for student-athlete computing lab.",  
      "• Redesigned departmental website and migrated legacy internal applications  to customized Wordpress web application.",  
      "• Administered Fedora Linux, Apache, MySQL."
  ]


}




],
  "volunteer": [{
    "organization": "New York Road Runners",
    "position": "Volunteer",
    "url": "https://www.nyrr.org/",
    "startDate": "2012-01-01",
    "endDate": "2013-01-01",
    "summary": "Description…",
    "highlights": [
      "Awarded Volunteer of the Month"
    ]
  }],
  "education": [{
    "institution": "Auburn University",
    "url": "https://www.auburn.edu/",
    "area": "Management Information Systems",
    "studyType": "Bachelor of Science in Business Administration",
    "startDate": "2011-01-01",
    "endDate": "2013-01-01",
    "score": "",
    "courses": [
      "ENGL 1100 English Composition I",
      "ENGL 1120 English Composition II",
      "HIST 1100 World History I", 
      "HIST 1100 World History II", 
      "SCMH 1010 Core Science I",
      "SCMH 1020 Core Science II",
      "COMM 1000 Public Speaking",
      "MATH 1680 Calculus with Business Applications I",
      "ECON 2020 Principles of Microeconomics",   
      "ECON 2030 Principles of Macroeconomics",
      "SCMN 2150 Ops: Management of Business Processes",
      "ACCT 2700 Business Law",
      "MNGT 3100 Principles of Management",
      "MKTG 3310 Principles of Marketing",
      "CTCT 3250 Information Analysis",
      "FINC 3610 Principles of Business Finance",
      "ISMN 2140 Introduction to Management Information Systems",
      "ISMN 5650 Application Development with Emerging Technologies",
      "ISMN 4090 Digital Business Design",
      "ISMN 3830 Database Management Systems",  
      "BUAL 5650 Enterprise Management of the Big Data Environment",
      "ACCT 2110 Principles of Financial Accounting", 
      "ACCT 2210 Principles of Managerial Accounting"
    ]
  }],
 "awards": [{
    "title": "3rd Place, 40-44 Age Group. Midnight Run 4M.",
    "date": "2019-01-01",
    "awarder": "NYRR",
    "summary": "Age Group Award"
  }
  ],
  "publications": [{
    "name": "Publication",
    "publisher": "Company",
    "releaseDate": "2014-10-01",
    "url": "https://publication.com",
    "summary": "Description…"
  }],
  "skills": [{
    "name": "Web Development",
    "level": "Master",
    "keywords": [
      "HTML",
      "CSS",
      "JavaScript"
    ]
  }],
  "languages": [{
    "language": "English",
    "fluency": "Native speaker"
  }],
"references": [{
    "name": "Jarrett Wold",
    "reference": "Director of Compliance, IAB Tech Lab"
  }]
  }



';
*/ 
  $array = json_decode($json, true);
//$data = [ 'data' => ['dfsdfs'=>'ds'], 'method' => 'GET', 'status'=> 200];
 // $json = new JsonResponse($data);
  //return $json; // ['#markup'=>'sdfsdf'];
  
  
  
  
  $a = ['#type' => 'markup', '#markup'=>'???'];
  return $a;

  }

  public function three() {
  $data = [ 'data' => ['dfsdfs'=>'ds'], 'method' => 'GET', 'status'=> 200];
  $json = new JsonResponse($data);
  return $json;
  
  }
}
