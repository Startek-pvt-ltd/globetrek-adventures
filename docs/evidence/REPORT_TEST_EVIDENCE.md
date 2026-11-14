# GlobeTrek Adventures — Report Test Evidence

**Source:** `st20345403_CSE5009_WRIT1.docx`, CSE5009 — Web Application Development.
**Evidence classification:** Historic screenshots and recorded outcomes from the submitted academic report — not freshly executed tests.

**Public privacy review:** Only a subset of test captures is published here. Other captures remain in the original academic report to avoid exposing account details or payment-form examples.

| Test | Reported scenario | Reported actual outcome | Original report screenshot |
| --- | --- | --- | --- |
| TC01 | Check Home Page | The Home page displayed successfully. | Original report capture withheld from public repository |
| TC02 | Check User Registration | Customer account created successfully. | Retained in original report (not publicly uploaded) |
| TC03 | Check User Login | Customer logged in successfully and dashboard displayed. | Retained in original report (not publicly uploaded) |
| TC04 | Check Package Search and Filter | Matching packages displayed successfully. | Original report capture withheld from public repository |
| TC05 | Check Package Details | Package details displayed successfully. | Original report capture withheld from public repository |
| TC06 | Check Booking Process | Booking completed successfully. | Original report capture withheld from public repository |
| TC07 | Check Payment Process (simulation) | Report calls it successful; implementation uses simulated payment only. | Retained in original report (not publicly uploaded) |
| TC08 | Check Contact Form | Inquiry submitted successfully. | Retained in original report (not publicly uploaded) |
| TC09 | Check Customer Dashboard | Customer dashboard displayed successfully. | Original report capture withheld from public repository |
| TC10 | Check Staff Dashboard | Staff dashboard displayed successfully. | Original report capture withheld from public repository |
| TC11 | Check Admin Dashboard | Admin dashboard displayed successfully. | Original report capture withheld from public repository |
| TC12 | Check Package Management | Package updated successfully. | Original report capture withheld from public repository |
| TC13 | Check Booking Management | Booking management completed successfully. | Retained in original report (not publicly uploaded) |
| TC14 | Check Form Validation | Validation messages displayed successfully. | Original report capture withheld from public repository |
| TC15 | Check Logout | User logged out and returned to login page. | Retained in original report (not publicly uploaded) |

## What these captures do and do not establish

- They are PNG image assets embedded in the original coursework DOCX report. Source media filenames and SHA-256 hashes are in [`SOURCE_PROVENANCE.json`](SOURCE_PROVENANCE.json).
- They support that these screens and outcomes were **documented** in the submitted report.
- They do **not** establish that a fresh build, a fresh integration test run, or an independent QA review passed today.
- Published gallery images have not been edited; the raw test screenshots are withheld. Review personally identifying details, contact information, or card-form inputs before publishing.
- Simulated card/payment steps should always be labelled **academic simulations**, never real settled transactions.

## Reproduction guidance

For fresh reproducible evidence, run the original project in a disposable local environment, use fictional test data, capture each use case, and record build/test commands, dates, versions, and expected/actual results. Preserve the original coursework implementation.
