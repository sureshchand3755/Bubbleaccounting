@extends('homeheader')
@section('content')
<style>
  .modulecontent .tab-pane p {
    line-height: 24px;
  }
</style>
    <div class="herosection">
      <div class="container-fluid">
        <div class="modules">
          <div class="row align-items-start">
            <div class="col-lg-6 col-md-6 col-sm-12">
              <ul class="nav flex-column nav-tabs border-0" id="myTab" role="tablist">
                <div class="row">
                  <div class="col-lg-4 col-md-6">
                    <li class="nav-item" role="presentation">
                      <button class="btn w-100 mb-2 active" id="task-manager" data-bs-toggle="tab"
                        data-bs-target="#task-manager-pane" type="button" role="tab" aria-controls="task-manager-pane"
                        aria-selected="true">Task Manager</button>
                    </li>
                    <li class="nav-item" role="presentation">
                      <button class="btn w-100 mb-2" id="time-management" data-bs-toggle="tab"
                        data-bs-target="#time-management-pane" type="button" role="tab"
                        aria-controls="time-management-pane" aria-selected="false">Time me Management</button>
                    </li>
                    <li class="nav-item" role="presentation">
                      <button class="btn w-100 mb-2" id="infiles-docs" data-bs-toggle="tab"
                        data-bs-target="#infiles-docs-pane" type="button" role="tab" aria-controls="infiles-docs-pane"
                        aria-selected="false">Infiles Document Management</button>
                    </li>
                    <li class="nav-item" role="presentation">
                      <button class="btn w-100 mb-2" id="payroll-solutions" data-bs-toggle="tab"
                        data-bs-target="#payroll-solutions-pane" type="button" role="tab"
                        aria-controls="payroll-solutions-pane" aria-selected="true">Payroll Management
                        System</button>
                    </li>
                    <li class="nav-item" role="presentation">
                      <button class="btn w-100 mb-2" id="year-end" data-bs-toggle="tab" data-bs-target="#year-end-pane"
                        type="button" role="tab" aria-controls="year-end-pane" aria-selected="true">Year End Management</button>
                    </li>
                    <li class="nav-item" role="presentation">
                      <button class="btn w-100 mb-2" id="tax-return" data-bs-toggle="tab"
                        data-bs-target="#tax-return-pane" type="button" role="tab" aria-controls="tax-return-pane"
                        aria-selected="true">Tax Return Liability Management</button>
                    </li>
                    <li class="nav-item" role="presentation">
                      <button class="btn w-100 mb-2" id="rct" data-bs-toggle="tab" data-bs-target="#rct-pane"
                        type="button" role="tab" aria-controls="rct-pane" aria-selected="true">Relevant Contract Tax
                        (RCT) Management</button>
                    </li>
                    <li class="nav-item" role="presentation">
                      <button class="btn w-100 mb-2" id="vat-management" data-bs-toggle="tab"
                        data-bs-target="#vat-management-pane" type="button" role="tab"
                        aria-controls="vat-management-pane" aria-selected="true">VAT Management</button>
                    </li>
                    <li class="nav-item" role="presentation">
                      <button class="btn w-100 mb-2" id="cro-management" data-bs-toggle="tab"
                        data-bs-target="#cro-management-pane" type="button" role="tab"
                        aria-controls="cro-management-pane" aria-selected="true">CRO Management</button>
                    </li>
                  </div>
                  <div class="col-lg-4 d-sm-none d-md-block hide_sm">
                    <li><img src="<?php echo URL::to('public/staticpages/img/modules.png'); ?>" alt="modules"></li>
                  </div>
                  <div class="col-lg-4 col-md-6">
                    <li class="nav-item" role="presentation">
                      <button class="btn w-100 mb-2" id="cr-system" data-bs-toggle="tab"
                        data-bs-target="#cr-system-pane" type="button" role="tab" aria-controls="cr-system-pane"
                        aria-selected="true">Client Request System</button>
                    </li>
                    <li class="nav-item" role="presentation">
                      <button class="btn w-100 mb-2" id="anti-money" data-bs-toggle="tab"
                        data-bs-target="#anti-money-pane" type="button" role="tab" aria-controls="anti-money-pane"
                        aria-selected="true">Anti Money Laundering</button>
                    </li>
                    <li class="nav-item" role="presentation">
                      <button class="btn w-100 mb-2" id="key-docs" data-bs-toggle="tab" data-bs-target="#key-docs-pane"
                        type="button" role="tab" aria-controls="key-docs-pane" aria-selected="true">Key Docs
                        System</button>
                    </li>
                    <li class="nav-item" role="presentation">
                      <button class="btn w-100 mb-2" id="ic-system" data-bs-toggle="tab"
                        data-bs-target="#ic-system-pane" type="button" role="tab" aria-controls="ic-system-pane"
                        aria-selected="true">Internal Comms
                        System (Bubble Mail)</button>
                    </li>
                    <li class="nav-item" role="presentation">
                      <button class="btn w-100 mb-2" id="internal-accounting" data-bs-toggle="tab"
                        data-bs-target="#internal-accounting-pane" type="button" role="tab"
                        aria-controls="internal-accounting-pane" aria-selected="true">Internal Accounting</button>
                    </li>
                    <li class="nav-item" role="presentation">
                      <button class="btn w-100 mb-2" id="purchase-management" data-bs-toggle="tab"
                        data-bs-target="#purchase-management-pane" type="button" role="tab"
                        aria-controls="purchase-management-pane" aria-selected="true">Supplier & Purchase
                        Management</button>
                    </li>
                    <li class="nav-item" role="presentation">
                      <button class="btn w-100 mb-2" id="client-billing" data-bs-toggle="tab"
                        data-bs-target="#client-billing-pane" type="button" role="tab"
                        aria-controls="client-billing-pane" aria-selected="true">Client Billing &
                        Statements</button>
                    </li>
                    <li class="nav-item" role="presentation">
                      <button class="btn w-100 mb-2" id="payment-receipt" data-bs-toggle="tab"
                        data-bs-target="#payment-receipt-pane" type="button" role="tab"
                        aria-controls="payment-receipt-pane" aria-selected="true">Payment & Receipt Management</button>
                    </li>
                    <li class="nav-item" role="presentation">
                      <button class="btn w-100 mb-2" id="banking" data-bs-toggle="tab" data-bs-target="#banking-pane"
                        type="button" role="tab" aria-controls="banking-pane" aria-selected="true">Banking & Bank
                        Reconciliation</button>
                    </li>
                  </div>
                </div>
              </ul>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
              <div class="modulecontent">
                <div class="tab-content" id="myTabContent">
                  <div class="tab-pane fade show active" id="task-manager-pane" role="tabpanel"
                    aria-labelledby="task-manager" tabindex="0" style="max-height: 700px;overflow-y: scroll;scrollbar-color: #3db0e6 #b8bfc8;scrollbar-width: thin;">
                    <h3>Introducing Bubble.ie's Task Manager Module: Streamline Your Practice's Workflow and Boost Efficiency</h3>
                    <p>The Task Manager Module offered by Bubble.ie is a powerful tool designed to revolutionize the way accounting practices manage their tasks and enhance overall performance. With a comprehensive range of features and benefits, this module simplifies task management, promotes collaboration, and increases productivity within your practice.</p>
                    <p><strong>Automate and Prioritize Tasks:</strong> The Task Manager Module enables you to automate routine tasks, freeing up valuable time and allowing you to focus on more important work. By setting due dates and prioritizing tasks, you can effectively manage your workload and ensure that critical tasks are completed on time. With the star labeling system, you can easily identify and organize tasks based on their importance, while the progress bar tools provide a visual representation of task status, allowing you to track progress at a glance.</p>
                    <p><strong>Efficient Task Assignment and Collaboration:</strong> Assigning tasks to team members is effortless with the Task Manager Module. You can allocate tasks to specific individuals, ensuring that responsibilities are clear and everyone knows what they need to do. This promotes accountability and streamlines collaboration within your practice. Additionally, the module allows tasks to be allocated, completed, and transferred between users, facilitating seamless handoffs and preventing any tasks from falling through the cracks.</p>
                    <p><strong>Easy Task Retrieval and Historical Tracking:</strong> Bubble.ie's Task Manager Module ensures that completed tasks are never lost or forgotten. Although tasks cannot be deleted, they can be marked as completed, enabling easy retrieval of information on previously finished tasks. This feature proves invaluable when reviewing past work or tracking progress over time. Should you need to cross-reference or extract data from other modules, such as Infiles or Year End Manager, the Task Manager Module provides convenient links to ensure a comprehensive and integrated workflow.</p>
                    <p><strong>Personalized Task Views and Planning:</strong> Each user within your practice has their own Task Manager, tailored to their specific workload and responsibilities. The main page displays tasks allocated to individual users, empowering them to plan their day efficiently. By considering task priorities, duration, and instructions from supervisors, users can create a daily plan that optimizes their productivity. The parked tasks feature allows users to temporarily set aside tasks that cannot be completed immediately, preventing unnecessary clutter and enabling focused planning.</p>
                    <p><strong>Advanced Search Capabilities:</strong> The Task Manager Module includes a robust task search function that enhances visibility and accessibility. Users can search for open or closed tasks, making it easy to locate specific information or review completed tasks for issue resolution. This search functionality contributes to improved efficiency and collaboration, allowing users to quickly find relevant tasks and take necessary actions.</p>
                    <p><strong>Client Task Analysis:</strong> For a holistic view of tasks associated with specific clients, the Task Manager Module includes a Client Task Analysis feature. This allows users to search for all tasks created for a particular client, providing a comprehensive overview of ongoing work and facilitating effective client management.</p>
                    <p><strong>User-Friendly and Secure:</strong> Usability is a top priority for Bubble.ie, and the Task Manager Module reflects this commitment. The interface is intuitive and user-friendly, ensuring that both technical experts and those without extensive technical expertise can navigate and utilize the module effortlessly. Additionally, Bubble.ie prioritizes the security and confidentiality of your data. The Task Manager Module stores information on a dedicated server with 32-bit encryption, guaranteeing the protection of your practice's sensitive data.</p>
                    <p>By leveraging the Task Manager Module from Bubble.ie, accounting practices can optimize task management, streamline workflows, and enhance overall performance. With its comprehensive features, efficient task assignment and tracking, personalized planning capabilities, advanced search functionality, and user-friendly interface, this module empowers practices of all sizes to achieve greater efficiency and productivity. Experience the future of task management in accounting practices today with Bubble.ie's Task Manager Module.</p>
                  </div>
                  <div class="tab-pane fade" id="time-management-pane" role="tabpanel" aria-labelledby="time-management" tabindex="0" style="max-height: 700px;overflow-y: scroll;scrollbar-color: #3db0e6 #b8bfc8;scrollbar-width: thin;">
                    <h3>Time Me Module: Streamline Time Tracking and Optimize Productivity</h3>
                    <p>The Time Me Module offered by Bubble.ie is a powerful time management tool designed to revolutionize the way accounting practices track and allocate time spent on various tasks and clients. With its comprehensive features and user-friendly interface, this module enhances efficiency, promotes accurate time tracking, and enables practices to optimize their overall performance.</p>
                    <p><strong>Accurate Time Tracking:</strong> The Time Me Module allows users to precisely track the time spent on each task and client, providing a transparent and reliable overview of where time is allocated. By clicking into the Time Me system, users can access a dedicated page where they can select a user, create an active job, create an even bulk job, or load a time sheet. This flexibility ensures that all time-related activities are accurately recorded and accounted for.</p>
                    <p><strong>Task and Client Allocation:</strong> Time Me enables users to allocate time spent to specific clients, ensuring accurate time attribution and enabling practices to accurately track and analyze client-specific resource allocation. Users can select clients from an active client list, manually enter client names, or choose from preset lists. Additionally, time can be allocated to in-house tasks such as admin work or email management, allowing for a comprehensive view of time spent on various activities within the practice.</p>
                    <p><strong>Efficient Time Batch Processing:</strong> For tasks that require time allocation across multiple clients, the Time Me Module offers efficient batch processing. By using one task and splitting the time evenly among the clients in a batch list, users can streamline time allocation and eliminate repetitive manual entries. This feature is particularly useful when working on multiple clients simultaneously, such as processing weekly payroll for a large number of clients.</p>
                    <p><strong>Comprehensive Time Analysis:</strong> The Time Me Module provides comprehensive time analysis capabilities, allowing users to view total time spent on any client, user, or specific tasks. This feature empowers practices to gain insights into resource allocation, identify areas of improvement, and make informed decisions about task prioritization and resource management.</p>
                    <p><strong>Seamless Integration with Active Job Management:</strong> The Time Me Module seamlessly integrates with the active job management system within Bubble.ie. Users can create an active job, select internal tasks such as file management or admin work, and record the start and stop times. This integration ensures a cohesive workflow where time spent on internal activities is accurately tracked alongside client-related tasks, providing a holistic view of time allocation within the practice.</p>
                    <p><strong>Flexible Reporting and Documentation:</strong> Bubble.ie's Time Me Module offers flexibility in reporting and documentation. Users have the option to include comments when stopping a particular job, allowing them to provide detailed information about the work done on a specific client or task. This feature enhances communication and documentation within the practice, facilitating knowledge sharing and ensuring clarity on completed activities.</p>
                    <p><strong>User-Friendly Interface:</strong> Usability is a top priority for Bubble.ie, and the Time Me Module reflects this commitment. The interface is intuitive and easy to navigate, allowing users to seamlessly record and track time without any technical expertise. The module's user-friendly design ensures that time tracking becomes a simple and efficient task, empowering all users within the practice to utilize the module effectively.</p>
                    <p>By leveraging the Time Me Module from Bubble.ie, accounting practices can streamline time tracking, improve accuracy, and optimize overall performance. With its accurate time allocation, client and task management capabilities, efficient batch processing, comprehensive time analysis, seamless integration with active job management, flexible reporting options, and user-friendly interface, this module empowers practices to enhance productivity, make data-driven decisions, and achieve greater efficiency in their daily operations. Experience the future of time management in accounting practices today with Bubble.ie's Time Me Module.</p>
                  </div>
                  <div class="tab-pane fade" id="infiles-docs-pane" role="tabpanel" aria-labelledby="infiles-docs" tabindex="0" style="max-height: 700px;overflow-y: scroll;scrollbar-color: #3db0e6 #b8bfc8;scrollbar-width: thin;">
                    <h3>Introducing Infiles: The Ultimate Document Management System</h3>
                    <p>Say goodbye to the chaos of paper files and scattered digital documents. With Infiles, our cutting-edge document management module, organizing and accessing your files has never been easier.</p>
                    <p>Infiles provides a secure and centralized storage solution for all your uploaded files. Whether it's bank statements, purchase invoices, sales receipts, or any other important documents, you can effortlessly store and manage them within our system. But we don't stop at just storage – Infiles goes above and beyond to enhance your document management experience.</p>
                    <p>Each file in Infiles can be accompanied by descriptive details that bring clarity and context to your documents. You can include vital information such as the supplier or customer associated with the document, a unique code for seamless integration with your accounts production software, date of the transaction, and even net figures for VAT calculations. Speaking of VAT, our intelligent system allows you to easily change VAT rates and automatically calculates net, VAT, and gross figures for complete accuracy.</p>
                    <p>To ensure consistency and efficiency, Infiles lets you upload supplier and customer lists, ensuring that nominal codes are populated correctly. This feature ensures that the names of each supplier or customer remain consistent, streamlining your accounting processes.</p>
                    <p>When it's time to review and analyse your data, Infiles simplifies the process. Figures entered into the system can be effortlessly totalled at the bottom of each file, providing a comprehensive overview for efficient VAT management. Moreover, you have the flexibility to download the entered data by type, such as sales and purchase ledgers, in convenient CSV format, ready for further analysis or integration with your preferred software.</p>
                    <p>Infiles empowers you to take control of your documents, streamline your accounting procedures, and eliminate the headaches associated with manual file management. Experience the power of organized, efficient, and secure document management with Infiles.</p>
                    <p>Discover the future of document management with Bubble.ie. Try Infiles today and witness the transformation of your accountancy practice.</p>
                  </div>
                  <div class="tab-pane fade" id="payroll-solutions-pane" role="tabpanel"
                    aria-labelledby="payroll-solutions" tabindex="0" style="max-height: 700px;overflow-y: scroll;scrollbar-color: #3db0e6 #b8bfc8;scrollbar-width: thin;">
                    <h3>Payroll Management System (PMS)</h3>
                    <p>The Payroll Management System (PMS) offered by Bubble.ie is a comprehensive module designed to simplify and optimize payroll tasks within accounting practices. With features such as efficient payroll tracking, seamless client communication, transparent payroll liabilities, remote document access, comprehensive time period management, enhanced data validation and reporting, and a user-friendly interface, the PMS empowers practices to deliver efficient and reliable payroll services.</p>
                    <p><strong>Efficient Payroll Tracking and Document Management:</strong> The PMS centralizes payroll tasks, files, and PAYE liabilities. It allows for easy upload of client payroll information and provides an organized overview of pending and completed tasks. Users can efficiently manage tasks, upload essential files, and securely store payroll documents.</p>
                    <p><strong>Seamless Client Communication:</strong> The PMS includes an email function for easy and timely distribution of payroll documents to clients. Users can send payslips, payroll summaries, and payment instructions, improving communication and client satisfaction.</p>
                    <p><strong>Transparent Payroll Liabilities:</strong> The PMS integrates with the PAYE MRS module, ensuring accurate and transparent payroll liabilities. It compares recorded liabilities with Revenue reports, reconciling any discrepancies and facilitating compliance.</p>
                    <p><strong>Remote Access to Payroll Documents:</strong> Clients can securely access their payslips and payroll summaries remotely, enhancing convenience and providing a self-service option for reviewing payroll information.</p>
                    <p><strong>Comprehensive Time Period Management:</strong> The PMS organizes payroll tasks by time periods (weekly or monthly) and categorizes tasks as standard or complex. This ensures accurate processing and easy navigation within the system.</p>
                    <p><strong>Enhanced Data Validation and Reporting:</strong> The PMS validates data by comparing recorded liabilities with Revenue reports, enabling timely adjustments for accurate reporting. Detailed reports on clients' PAYE liabilities facilitate clear communication and efficient payment management.</p>
                    <p><strong>User-Friendly Interface and Navigation:</strong> The PMS features an intuitive interface, allowing users to navigate tasks, upload documents, manage liabilities, and initiate email communications with ease.</p>
                    <p>By leveraging Bubble.ie's Payroll Management System (PMS), accounting practices can streamline payroll processing, improve accuracy, and enhance overall performance. Experience the future of payroll management with Bubble.ie's PMS.</p>
                  </div>
                  <div class="tab-pane fade" id="year-end-pane" role="tabpanel" aria-labelledby="year-end" tabindex="0" style="max-height: 700px;overflow-y: scroll;scrollbar-color: #3db0e6 #b8bfc8;scrollbar-width: thin;">
                    <h3>Discover Bubble.ie's Year End Manager: Simplified Year-End Accounts Tracking System</h3>
                    <p>Year End Manager from Bubble.ie is your ultimate solution for efficient year-end accounts tracking. This module streamlines document organization and boosts collaboration among team members.</p>
                    <p><strong>Effortless Management:</strong> Year End Manager centralizes client lists for year-end accounts. Easily add completed documents like Form 11, Financial Statements, and more. Client-specific screens simplify document storage.</p>
                    <p><strong>Integrated Tasks:</strong> Associate each year-end account with a task like "Post Accounts." Completing a task links it to Year End Manager, reducing steps and offering instant document access.</p>
                    <p><strong>Collaborative Reviews:</strong> Upload PDFs of accounts, tax returns, invoices, etc., for review. Track progress from "Not Started" to "Complete." Enhance transparency and accountability.</p>
                    <p><strong>Document Tracking:</strong> Effortlessly track financial statements, tax returns, and more. Link them to Task Manager for client tasks. Mark documents as completed upon client approval.</p>
                    <p><strong>Financial Insights:</strong> Present figures alongside tax returns for clear liability or refund representation. Linked to Liability Assessment Manager for comprehensive insights.</p>
                    <p><strong>User-Friendly Interface:</strong> Navigate, upload, and access with ease. Suitable for all users, regardless of technical skills.</p>
                    <p>Experience streamlined year-end accounts trakcing with Bubble.ie's Year End Manager. Elevate efficiency and organization in your practice today.</p>
                  </div>
                  <div class="tab-pane fade" id="tax-return-pane" role="tabpanel" aria-labelledby="tax-return" tabindex="0" style="max-height: 700px;overflow-y: scroll;scrollbar-color: #3db0e6 #b8bfc8;scrollbar-width: thin;">
                    <h3>Year End Tax Liability Assessment System: Streamlining Tax Compliance and Liability Management</h3>

                    <ul><li>Ensure seamless tax compliance and efficient management of tax liabilities with our Year End Tax Liability Assessment System. This system forms an integral part of the tax return preparation and submission process, aimed at guaranteeing timely payment of tax liabilities and obtaining rightful refunds for clients.</li></ul>

                    <h3>Features and Benefits:</h3>
                    <ul>
                        <li><strong>Holistic Tax Management:</strong> Seamlessly integrated with the Year End Management system, our Tax Liability Assessment system simplifies the process of managing tax liabilities for clients. This ensures a comprehensive approach to tax compliance and liability management.</li>
                        <li><strong>Efficient Liability Calculation:</strong> The system pulls relevant data from the Year End Management system, enabling the calculation of accurate tax liabilities. This eliminates manual errors and streamlines the assessment process.</li>
                        <li><strong>Timely Payment Assurance:</strong> By providing a clear overview of tax liabilities due to revenue, the system assists your practice in ensuring that all necessary tax payments are made on time. This proactive approach minimizes the risk of late payments and associated penalties.</li>
                        <li><strong>Refund Facilitation:</strong> The system also aids in identifying and pursuing rightful tax refunds due to clients. This ensures that your clients receive their entitled refunds promptly and without unnecessary delays.</li>
                        <li><strong>Comprehensive Tracking:</strong> Gain insight into the balancing liability status for each client, enabling effective tracking of liabilities as well as refunds. This comprehensive tracking mechanism offers transparency and accountability.</li>
                        <li><strong>Simplified Management:</strong> Benefit from an easy-to-manage process that centralizes tax liability assessment and management. This consolidation of tasks reduces administrative burden and enhances overall practice efficiency.</li>
                        <li><strong>Enhanced Client Satisfaction:</strong> By ensuring accurate and timely tax payments and refunds, your practice demonstrates a commitment to your clients' financial well-being. This contributes to client satisfaction and strengthens your client relationships.</li>
                    </ul>
                    <ul><li>
                    Elevate your tax return process and take control of tax liabilities and refunds with the Year End Tax Liability Assessment System. Experience streamlined tax compliance management that enhances your practice's performance and strengthens client trust.</li></ul>
                  </div>
                  <div class="tab-pane fade" id="rct-pane" role="tabpanel" aria-labelledby="rct" tabindex="0" style="max-height: 700px;overflow-y: scroll;scrollbar-color: #3db0e6 #b8bfc8;scrollbar-width: thin;">
                    <h3>Discover Bubble.ie's RCT Management System: Simplified RCT Control</h3>
                    <p>Simplify Relevant Contracts Tax (RCT) with Bubble.ie's RCT Management System. Effortlessly manage payment notifications and contracts for principal contractors. Streamline processes, from payment submission to client notifications.</p>
                    <p><strong>Effortless Payment Tracking:</strong> Track subcontractor payments and contracts for principal contractors. Instantly upload payment notifications to the system and email clients with details like subcontractor names, payment amounts, and RCT deductions.</p>
                    <p><strong>Clear Reporting:</strong> Access detailed reports on payment notifications and RCT deductions by month. Ensure smooth compliance with RCT monthly returns.</p>
                    <p><strong>Efficient Workflow:</strong> Navigate the RCT manager screen to upload payment details and notify clients. Download comprehensive CSV or PDF files of client notifications.</p>
                    <p><strong>Transparent Assessment:</strong> Evaluate client notifications conveniently by month. Streamline tracking and assessment for accurate reporting.</p>
                    <p><strong>Simple Email Alerts:</strong> Easily manage payment notifications and contracts. Select the month, check relevant boxes, and initiate batch emails with subcontractor details.</p>
                    <p>Experience streamlined RCT management with Bubble.ie's RCT Management System. Optimize RCT processes for efficient compliance and accuracy.</p>
                  </div>
                  <div class="tab-pane fade" id="vat-management-pane" role="tabpanel" aria-labelledby="vat-management" tabindex="0" style="max-height: 700px;overflow-y: scroll;scrollbar-color: #3db0e6 #b8bfc8;scrollbar-width: thin;">
                      <h3>Introducing Bubble.ie's VAT Management Module: Streamlined VAT Control</h3>
                      <p>Discover the power of effortless VAT management with Bubble.ie's VAT Management Module. This module is designed to streamline VAT returns, approvals, and submissions for enhanced efficiency and compliance.</p>
                      <p><strong>Seamless Client Tracking:</strong> Bubble.ie's VAT Management System lists all clients and their corresponding tax numbers. Integrating with ROS, this module provides real-time visibility into clients with VAT submissions due. Once records are received, a simple checkbox allows for easy tracking, turning green when records are in and ready for processing.</p>
                      <p><strong>Efficient VAT Completion:</strong> Upon completing VAT calculations, input T1 and T2 figures for review and approval by relevant staff members. Approvals are conveniently managed, with a comments box for detailed instructions. Upon approval, submissions are sent to Revenue, generating VAT3 returns. These returns, along with associated comments, are attached within the VAT Management System.</p>
                      <p><strong>Enhanced Submission Process:</strong> The system offers a comprehensive overview of submissions for each month. Quickly assess submission statuses—submitted, submission due, potentially submitted, or submission late—enabling efficient decision-making. Sorting by 'approve status' streamlines approval-ready submissions, while an export to Excel option facilitates timesheet management.</p>
                      <p><strong>Detailed Client Insights:</strong> Explore client-specific VAT returns effortlessly. Review submission statuses, dates, and attached VAT3s. Easily download VAT3s for specific accounting periods and track amendments or late submissions.</p>
                      <p><strong>Automatic Populations and Tracking:</strong> The VAT Management Module automates much of the process. Returns due are tracked through ROS extracts, while completed infiles for clients populate T1 and T2 figures. Approval tracking ensures a transparent and organized workflow.</p>
                      <p><strong>Experience the Future of VAT Management:</strong> Bubble.ie's VAT Management Module revolutionizes VAT control, from tracking submissions to approvals and streamlined processes. Elevate your VAT management with enhanced accuracy and compliance. Explore Bubble.ie's VAT Management Module today.</p>
                  </div>
                  <div class="tab-pane fade" id="cro-management-pane" role="tabpanel" aria-labelledby="cro-management" tabindex="0" style="max-height: 700px;overflow-y: scroll;scrollbar-color: #3db0e6 #b8bfc8;scrollbar-width: thin;">
                    <h3>Introducing Bubble.ie's CRO ARD System: Streamlined Annual Return Management</h3>
                    <p>Explore the efficiency of Bubble.ie's CRO ARD System, a comprehensive solution designed to simplify annual return submissions for Limited Company clients. Seamlessly integrated with the Companies Registration Office (CRO), this module ensures timely submissions and accurate record-keeping.</p>
                    <p><strong>Effortless Annual Return Tracking:</strong> Bubble.ie's CRO ARD System stores vital client information and Annual Return Dates (ARD). This feature eliminates the risk of late submissions and keeps you on top of compliance requirements.</p>
                    <p><strong>Timely Submission Facilitation:</strong> Manage the entire annual return process effortlessly. Once the return is prepared and ready for client signature, it can be uploaded into the system. Upon receiving the signed form, the module facilitates seamless submission to the CRO. A simple checkbox confirms the form's submission, and the ARD updates automatically to the next year.</p>
                    <p><strong>Comprehensive Record-Keeping:</strong> Experience enhanced record-keeping with Bubble.ie's CRO ARD System. Monitor clients' compliance status and ensure they are up to date with the CRO in real time. The system also enables efficient tracking of Beneficial Ownership submissions to the Register of Beneficial Ownership (RBO).</p>
                    <p><strong>Real-Time Updates:</strong> Enjoy the convenience of real-time updates. With the CRO ARD System, signed forms and submissions are immediately reflected in the system, ensuring accurate and up-to-date information at all times.</p>
                    <p><strong>Seamless Integration:</strong> Bubble.ie's CRO ARD System harmonizes with your workflow, ensuring a smooth transition from document preparation to submission. Simplify compliance tasks, eliminate the risk of late submissions, and maintain accurate records effortlessly.</p>
                    <p><strong>Elevate Your Annual Return Management:</strong> Discover Bubble.ie's CRO ARD System and revolutionize your annual return management process. Enhance accuracy, maintain compliance, and streamline operations with this essential module. Explore the benefits of Bubble.ie's CRO ARD System today.</p>
                  </div>
                  <div class="tab-pane fade" id="cr-system-pane" role="tabpanel" aria-labelledby="cr-system" tabindex="0" style="max-height: 700px;overflow-y: scroll;scrollbar-color: #3db0e6 #b8bfc8;scrollbar-width: thin;">
                    <h3>Introducing Bubble.ie's Client Request System: Streamlined Information Retrieval</h3>
                    <p>Experience the power of Bubble.ie's Client Request System, a dynamic solution designed to simplify the process of gathering crucial client information for seamless financial statement completion. This comprehensive module offers a range of features tailored to enhance efficiency and collaboration.</p>
                    <p><strong>Effortless Information Gathering:</strong> Bubble.ie's Client Request System serves as a centralized hub for all client-related information needs. Easily request missing data required for year-end accounts, bookkeeping, payroll, VAT, and more. With user-friendly options, tailor your requests to specific categories, years, and staff members, ensuring clarity and accuracy.</p>
                    <p><strong>Seamless Request Initiation:</strong> The process is simple yet robust. Select the client on the main screen, create a new request, and specify the information required. This system allows you to categorize the needed data, such as bank statements, sales invoices, and purchase invoices, making it clear for both you and the client.</p>
                    <p><strong>Efficient Approval Workflow:</strong> Streamline the approval process by sending the request for approval directly to the relevant staff member. Alternatively, send it directly to the client, expediting the information retrieval process. This flexibility ensures swift communication and collaboration within your team.</p>
                    <p><strong>Comprehensive Tracking and Collaboration:</strong> With Bubble.ie's Client Request System, keep tabs on each request's status effortlessly. Identify outstanding requests, those awaiting approval, and those already fulfilled. This transparency fosters effective collaboration among team members and clients.</p>
                    <p><strong>Tailored Information Retrieval:</strong> Select the desired year for the information you need, ensuring that your data retrieval aligns perfectly with your financial statement requirements.</p>
                    <p><strong>Elevate Your Information Retrieval Process:</strong> Revolutionize how you gather client information with Bubble.ie's Client Request System. Enhance communication, streamline collaboration, and expedite the information retrieval process. Unleash the benefits of efficient data gathering with Bubble.ie's Client Request System today.</p>
                  </div>
                  <div class="tab-pane fade" id="anti-money-pane" role="tabpanel" aria-labelledby="anti-money" tabindex="0" style="max-height: 700px;overflow-y: scroll;scrollbar-color: #3db0e6 #b8bfc8;scrollbar-width: thin;">
                    <h3>Introducing Bubble's AML System: Your Shield Against Financial Crime</h3>
                    
                    <ul>
                    <li>Bubble's AML System is your comprehensive solution for Anti Money Laundering (AML) compliance, fraud prevention, and Know Your Client (KYC) processes. It efficiently tracks client IDs, key dates, and trade information while flagging potential money laundering risks, such as identifying/tracking politically exposed persons.</li>
                    </ul>
                    
                    <ul>
                      <li><strong>Seamless Integration, Reduced Burden:</strong><br>Our AML System seamlessly integrates with other modules, streamlining your practice's AML processes. It reduces administrative burdens, cuts costs, and ensures compliance, making it your trusted partner in safeguarding financial integrity.</li><br>
                      
                      <li><strong>Stay Compliant, Stay Secure:</strong><br>Bubble's AML System empowers your practice with the tools needed to meet AML and KYC requirements. Protect your clients and your practice while upholding the highest standards of financial security.</li><br>
                      
                      <li><strong>Bubble's AML System – Where Financial Security Begins:</strong><br>Empower your practice today. Visit Bubble.ie to discover how our AML System can revolutionize your approach to AML compliance and fraud prevention.<br><br>

                      <i>Don't just manage AML. Master it with Bubble's AML System.</i></li>
                    </ul>
                  </div>
                  <div class="tab-pane fade" id="key-docs-pane" role="tabpanel" aria-labelledby="key-docs" tabindex="0" style="max-height: 700px;overflow-y: scroll;scrollbar-color: #3db0e6 #b8bfc8;scrollbar-width: thin;">
                    <h3>Key Docs System: Streamlined Document Management for Your Practice</h3>
                    <ul>
                    <li>Discover a seamless approach to managing essential client documents with our Key Docs System. Tailored for Accountancy Practices, this system serves as a centralized repository for critical records, ranging from Accountancy Invoices and Yearend Documents to Tax Clearance and Company Formation files.</li>
                    </ul>
                    
                    <h3>Features and Benefits:</h3>
                    <ul>
                      <li><strong>Comprehensive Document Storage:</strong> Access and store a range of vital documents, including Accountancy Invoices, Yearend Documents, Accountancy Letters, Tax Clearance, and Company Formation records.</li>
                      <li><strong>Effortless Accessibility:</strong> Retrieve Accountancy Practice-issued invoices historic to current, ensuring easy access to historical financial data.</li>
                      <li><strong>Enhanced Organization:</strong> Efficiently manage Yearend Docs, encompassing Form 11, Accounts, Corporation Tax returns, year-end letters, and Invoices, ensuring easy reference.</li>
                      <li><strong>Tax Compliance Visibility:</strong> Attach Tax Clearance records obtained from ROS, providing clear visibility into clients' tax compliance status.</li>
                      <li><strong>Seamless Company Formation Management:</strong> Store and manage Company Formation documents in one place, simplifying company setup record management.</li>
                      <li><strong>Efficient Retrieval and Communication:</strong> Easily upload and deliver documents such as tax clearance certificates and signed company formation papers to clients, streamlining communication.</li>
                      <li><strong>Time-Saving Document Sharing:</strong> Integration with the year-end manager facilitates quick inclusion of documents in email communications, saving time and enhancing client interactions.</li>
                    </ul>

                    <ul>
                    <li>Experience streamlined document management that enhances your practice's efficiency and organization. Elevate your client service with the Key Docs System and transform the way you manage essential documents.</li>
                    </ul>
                  </div>
                  <div class="tab-pane fade" id="ic-system-pane" role="tabpanel" aria-labelledby="ic-system" tabindex="0" style="max-height: 700px;overflow-y: scroll;scrollbar-color: #3db0e6 #b8bfc8;scrollbar-width: thin;">
                    <h3>Streamline Client Communication with Bubble Mail & Message Us</h3>
                    
                    <ul>
                    <li>Within the Bubble system, we've perfected client communication. Bubble Mail meticulously tracks automated emails and interactions, ensuring no details are overlooked. The Message Us feature streamlines bulk messaging, group creation, and scheduling for effortless client engagement. These systems are seamlessly integrated into your practice's workflow, providing both efficiency and comprehensive organization. Visit <a href="https://Bubble.ie">Bubble.ie</a> to discover how our tools can elevate your communication processes and enhance client interactions.<br><br><i>Elevate your practice with Bubble Mail & Message Us: where communication meets efficiency.</i></li>
                    </ul>
                  </div>
                  <div class="tab-pane fade" id="internal-accounting-pane" role="tabpanel"
                    aria-labelledby="internal-accounting" tabindex="0" style="max-height: 700px;overflow-y: scroll;scrollbar-color: #3db0e6 #b8bfc8;scrollbar-width: thin;">
                    <h3>Simplify Your Practice's Accounting with Bubble's Internal Accounting System</h3>
                    
                    <ul>
                    <li>Our Internal Accounting System is the beating heart of your practice's financial management. It seamlessly integrates data from various modules like Client Invoicing, Supplier Management, Payment & Receipt Tracking, and our robust Banking Module. This integration forms the foundation of your practice's financial records, allowing you to effortlessly handle VAT liabilities, journal entries, and the preparation of comprehensive trial balances, profit and loss accounts, and balance sheets.</li><br>
                    <li>This system is designed to streamline your internal accounting team's efforts, making the processing of management and year-end accounts more efficient than ever. Discover how Bubble's Internal Accounting System can revolutionize your practice's financial management and boost your performance.<br><br>

                    <i>Elevate your practice's financial management with Bubble's Internal Accounting System: where efficiency meets precision.</i></li>
                    </ul>
                  </div>
                  <div class="tab-pane fade" id="purchase-management-pane" role="tabpanel"
                    aria-labelledby="purchase-management" tabindex="0" style="max-height: 700px;overflow-y: scroll;scrollbar-color: #3db0e6 #b8bfc8;scrollbar-width: thin;">
                    <h3>Enhance Your Practice's Credit Management with the Supplier and Purchase Management Modules</h3>
                    
                    <ul>
                    <li>The Supplier and Purchase Invoice Management System simplifies your practice's internal accounts and creditor processing. It seamlessly integrates with your VAT returns management and full accounts. With this system, you can effortlessly create and maintain supplier records while keeping track of outstanding balances. Easily record purchase invoices using a drag-and-drop feature, and store supplier invoices for future reference. This module also connects with the Payment Management system, providing a streamlined approach to supplier management. Managing suppliers, their invoices, and staying up-to-date on your practice's financial obligations has never been easier.</li><br>
                    <li>Enhance your practice's performance with our Supplier and Purchase Invoice Management System, designed to simplify creditor reconciliation, improve VAT returns management, and streamline your internal accounts.</li>
                  </div>
                  <div class="tab-pane fade" id="client-billing-pane" role="tabpanel" aria-labelledby="client-billing" tabindex="0" style="max-height: 700px;overflow-y: scroll;scrollbar-color: #3db0e6 #b8bfc8;scrollbar-width: thin;">
                    <h3>Client Billing & Statements Module</h3>
                    
                    <ul>
                    <li>Our Client Billing & Statements Module revolutionizes debtor processing and management for your practice. This comprehensive system offers a unique "2Bill" feature, enabling operators to record non-standard tasks for future billing. It seamlessly integrates with the Time Management system, allowing you to assess your margins per invoice and per client with precision.</li><br>
                    <li>This module simplifies invoice distribution, making it effortless to send invoices to clients and redistribute them when needed. It also links to the Receipts Management module, facilitating payment recording and the creation of monthly client statements.</li><br>
                    <li>Experience quick, cost-effective, and efficient debtor management, supported by easy review capabilities for your senior practice managers. Elevate your practice's performance with our Client Billing & Statements Module, turning billing complexities into a streamlined and effective process.</li>
                  </div>
                  <div class="tab-pane fade" id="payment-receipt-pane" role="tabpanel" aria-labelledby="payment-receipt" tabindex="0" style="max-height: 700px;overflow-y: scroll;scrollbar-color: #3db0e6 #b8bfc8;scrollbar-width: thin;">
                    <h3>Payment and Receipts Modules</h3>
                      <ul>
                        <li>Effortlessly Manage Financial Transactions<br><br>Our Payment and Receipts Modules are the cornerstone of efficient financial management for your practice. They simplify the process of handling payments made to and received from third parties, streamlining your accounting procedures.</li>
                      </ul>
                      <h3 style="text-align: left;">Key Features:</h3>
                      <ol>
                        <li><strong>User-Friendly Interface:</strong> Our intuitive interface ensures that managing payments and receipts is a hassle-free experience. Operators can easily navigate the system, making data input simple and straightforward.</li><br>
                        
                        <li><strong>Seamless Integration:</strong> These modules are tightly aligned with our internal accounting modules, bank processing, reconciliation modules, and general practice management systems. This integration ensures that your financial data is always up-to-date and accurate.</li><br>
                        
                        <li><strong>Import Functionality:</strong> Save time and reduce errors by utilizing our import feature. Easily import data, further streamlining the payment and receipt process.</li><br>
                        
                        <li><strong>Multi-Account Support:</strong> Our system supports transactions from multiple bank and cash accounts, providing flexibility for your practice's unique financial needs.</li>
                      </ol>
    
                      <ul>
                        <li>With the Payment and Receipts Modules, you can effectively manage your practice's financial transactions in one unified platform. Simplify your accounting, improve accuracy, and enhance your practice's overall performance.</li>
                      </ul>
                  </div>
                  <div class="tab-pane fade" id="banking-pane" role="tabpanel" aria-labelledby="banking" tabindex="0" style="max-height: 700px;overflow-y: scroll;scrollbar-color: #3db0e6 #b8bfc8;scrollbar-width: thin;">
                    <h3>Banking & Bank Reconciliation Modules</h3>
                      <ul>
                        <li>Effortless Banking Management and Reconciliation<br><br>
                        Our Banking and Bank Reconciliation Modules are designed to streamline your practice's internal account management. These modules are essential for maintaining financial accuracy and efficiency.</li>
                      </ul>
                      <h3 style="text-align: left;">Key Features:</h3>
                      <ol>
                        <li><strong>Quick Reconciliation:</strong> Transform the often complex bank reconciliation process into a quick and straightforward task. Easily match transactions from the Payments and Receipts Modules with your bank records.</li><br>
                        
                        <li><strong>Interbank Transfer Identification:</strong> Identify and reconcile interbank transfers with ease, ensuring that your internal accounting accurately reflects all financial activities.</li><br>
                        
                        <li><strong>Comprehensive Banking Modules:</strong> Access a comprehensive set of banking tools that simplify your internal accounting processes. Whether you're managing deposits, withdrawals, or transfers, our modules provide the functionality you need.</li><br>
                        
                        <li><strong>Seamless Integration:</strong> These modules seamlessly integrate with your internal accounting processes. All banking journal entries are quickly posted, ensuring that your practice's financial data is up-to-date and error-free.</li><br>
                        
                        <li><strong>Full Accounting Support:</strong> The reconciliation process ties directly into your practice's overall journal, trial balance, management, and full accounts processing. This ensures that your practice remains compliant and efficient in all financial matters.</li>
                      </ol>
                      
                      <ul>
                        <li>With our Banking and Bank Reconciliation Modules, you can confidently manage your practice's internal accounts. Simplify reconciliation, enhance financial accuracy, and streamline your accounting processes. Your practice will benefit from increased efficiency and improved financial management.</li>
                      </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

  </div>

  </div>
@stop