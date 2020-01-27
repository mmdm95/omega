<?php defined('BASE_PATH') OR exit('No direct script access allowed'); ?>

<!-- Main navbar -->
<?php $this->view("templates/be/mainnavbar", $data); ?>
<!-- /main navbar -->
<!-- Page container -->
<div class="page-container">
    <!-- Page content -->
    <div class="page-content">
        <input type="hidden" id="BASE_URL" value="<?= base_url(); ?>">

        <!-- Main sidebar -->
        <?php $this->view("templates/be/mainsidebar", $data); ?>
        <!-- /main sidebar -->
        <!-- Main content -->
        <div class="content-wrapper">
            <!-- Page header -->
            <div class="page-header page-header-default"
                 style="border-top: 1px solid #ddd; border-left: 1px solid #ddd; border-right: 1px solid #ddd;">
                <div class="page-header-content border-bottom border-bottom-success">
                    <div class="page-title">
                        <h5>
                            <i class="icon-circle position-left"></i>
                            <span class="text-semibold">
                                سؤالات متداول
                </span>
                        </h5>
                    </div>
                </div>
                <div class="breadcrumb-line">
                    <ul class="breadcrumb">
                        <li>
                            <a href="<?= base_url(); ?>admin/index">
                                <i class="icon-home2 position-left"></i>
                                داشبورد
                            </a>
                        </li>
                        <li class="active">سؤالات متداول</li>
                    </ul>
                </div>
            </div>
            <!-- /page header -->
            <!-- Content area -->
            <div class="content">
                <!-- Centered forms -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-md-12">
                                <?php if (isset($errors) && count($errors)): ?>
                                    <div class="alert alert-danger alert-styled-left alert-bordered
                                                 no-border-top no-border-right no-border-bottom">
                                        <ul class="list-unstyled">
                                            <?php foreach ($errors as $err): ?>
                                                <li>
                                                    <i class="icon-dash" aria-hidden="true"></i>
                                                    <?= $err; ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php elseif (isset($success)): ?>
                                    <div class="alert alert-success alert-styled-left alert-bordered
                                                 no-border-top no-border-right no-border-bottom">
                                        <p>
                                            <?= $success; ?>
                                        </p>
                                    </div>
                                <?php endif; ?>

                                <form action="<?= base_url(); ?>admin/manageFAQ/<?= @$param[0] . '/' . @$param[1]; ?>"
                                      method="post">
                                    <?= $data['form_token']; ?>

                                    <div class="panel panel-body border-top-primary text-center">
                                        <h6 class="no-margin text-semibold">
                                            <?php if (isset($param[0]) && strtolower($param[0]) == 'edit'): ?>
                                                ویرایش سؤال
                                            <?php else: ?>
                                                افزودن سؤال
                                            <?php endif; ?>
                                        </h6>
                                        <p class="text-muted content-group-sm">
                                        </p>
                                        <div class="row">
                                            <div class="form-group col-md-12 mt-10">
                                                <textarea rows="5" cols="12" class="form-control"
                                                          name="question"
                                                          style="min-height: 100px; resize: vertical;"
                                                          placeholder="سؤال"><?= $faqVals['question'] ?? ''; ?></textarea>
                                            </div>
                                            <div class="form-group col-md-12 mt-10">
                                                <textarea rows="5" cols="12" class="form-control"
                                                          name="answer"
                                                          style="min-height: 100px; resize: vertical;"
                                                          placeholder="پاسخ"><?= $faqVals['answer'] ?? ''; ?></textarea>
                                            </div>
                                            <div class="text-right col-md-12">
                                                <?php if (isset($param[0]) && strtolower($param[0]) == 'edit'): ?>
                                                    <a href="<?= base_url(); ?>admin/manageFAQ" class="btn btn-success">
                                                        سوال جدید
                                                        <i class="icon-question6 position-right" aria-hidden="true"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <button type="submit" class="btn btn-primary">
                                                    <?php if (isset($param[0]) && strtolower($param[0]) == 'edit'): ?>
                                                        ویرایش
                                                    <?php else: ?>
                                                        افزودن
                                                    <?php endif; ?>
                                                    <i class="icon-arrow-left12 position-right"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-12">
                                <div class="panel panel-white">
                                    <div class="panel-heading">
                                        <h6 class="panel-title">لیست سؤالات</h6>
                                        <div class="heading-elements">
                                            <ul class="icons-list">
                                                <li><a data-action="collapse"></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <?php if (count($faqs)): ?>
                                        <div class="panel-body">
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>سؤال</th>
                                                        <th>پاسخ</th>
                                                        <th>عملیات</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <!-- Load users data -->
                                                    <?php foreach ($faqs as $key => $faq): ?>
                                                        <tr>
                                                            <td>
                                                                <?= convertNumbersToPersian(($offset++) + 1); ?>
                                                            </td>
                                                            <td>
                                                                <?= $faq['question']; ?>
                                                            </td>
                                                            <td>
                                                                <?= $faq['answer']; ?>
                                                            </td>
                                                            <td style="min-width: 95px;">
                                                                <ul class="icons-list">
                                                                    <li class="text-primary-600">
                                                                        <a href="<?= base_url(); ?>admin/manageFAQ/edit/<?= $faq['id']; ?>"
                                                                           title="ویرایش" data-popup="tooltip">
                                                                            <i class="icon-pencil7"></i>
                                                                        </a>
                                                                    </li>
                                                                    <li class="text-danger-600">
                                                                        <a class="deleteFAQBtn"
                                                                           title="حذف" data-popup="tooltip">
                                                                            <input type="hidden"
                                                                                   value="<?= $faq['id']; ?>">
                                                                            <i class="icon-trash"></i>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <?php if ($total != 0 && ($lastPage - $firstPage) != 0): ?>
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-ms-12 text-center ltr">
                                                            <ul class="pagination pagination-rounded">
                                                                <li class="<?php if ($firstPage == $page) echo 'disabled'; ?>">
                                                                    <a href="<?php if ($firstPage != $page) {
                                                                        echo base_url() . 'admin/manageFAQ/page/1';
                                                                    } ?>">
                                                                        <i class="icon-arrow-right13"
                                                                           aria-hidden="true"></i>
                                                                    </a>
                                                                </li>

                                                                <?php if (($page - 4) > $firstPage): ?>
                                                                    <li class="disabled">
                                                                        <a>
                                                                            ...
                                                                        </a>
                                                                    </li>
                                                                <?php endif; ?>
                                                                <?php for ($i = $page - 4; $i < $page; $i++): ?>
                                                                    <?php if ($i <= 0) continue; ?>
                                                                    <li class="<?php if ($i == $page) echo 'active'; ?>">
                                                                        <a href="<?php echo base_url() . 'admin/manageFAQ/page/' . $i; ?>">
                                                                            <?= convertNumbersToPersian($i); ?>
                                                                        </a>
                                                                    </li>
                                                                <?php endfor; ?>
                                                                <?php for ($i = $page; $i <= $page + 4 && $i <= $lastPage; $i++): ?>
                                                                    <li class="<?php if ($i == $page) echo 'active'; ?>">
                                                                        <a href="<?php echo base_url() . 'admin/manageFAQ/page/' . $i; ?>">
                                                                            <?= convertNumbersToPersian($i); ?>
                                                                        </a>
                                                                    </li>
                                                                <?php endfor; ?>
                                                                <?php if (($page + 4) < $lastPage): ?>
                                                                    <li class="disabled">
                                                                        <a>
                                                                            ...
                                                                        </a>
                                                                    </li>
                                                                <?php endif; ?>

                                                                <li class="<?php if ($lastPage == $page) echo 'disabled'; ?>">
                                                                    <a href="<?php if ($lastPage != $page) {
                                                                        echo base_url() . 'admin/manageFAQ/page/' . $lastPage;
                                                                    } ?>">
                                                                        <i class="icon-arrow-left12"
                                                                           aria-hidden="true"></i>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-center bg-default p-10">
                                            <h6>
                                                موردی یافت نشد.
                                            </h6>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /form centered -->
                <!-- Footer -->
                <?php $this->view("templates/be/copyright", $data); ?>
                <!-- /footer -->
            </div>
            <!-- /content area -->
        </div>
        <!-- /main content -->
    </div>
    <!-- /page content -->
</div>
<!-- /page container -->