 @extends('layouts.app')
    2
    3 @section('content')
    4 <div class="container py-5">
    5     <div class="row justify-content-center">
    6         <div class="col-md-8">
    7             <div class="card shadow-sm">
    8                 <div class="card-header bg-primary text-white">
    9                     <h4 class="mb-0">Create New Fair (Admin)</h4>
   10                 </div>
   11                 <div class="card-body">
   12                     @if(session('success'))
   13                         <div class="alert alert-success border-0 shadow-sm">
   14                             {{ session('success') }}
   15                         </div>
   16                     @endif
   17
   18                     @if($errors->any())
   19                         <div class="alert alert-danger border-0 shadow-sm">
   20                             <ul class="mb-0">
   21                                 @foreach($errors->all() as $error)
   22                                     <li>{{ $error }}</li>
   23                                 @endforeach
   24                             </ul>
   25                         </div>
   26                     @endif
   27
   28                     <form action="{{ route('admin.fairs.store') }}" method="POST">
   29                         @csrf
   30                         <div class="row mb-3">
   31                             <div class="col-md-12">
   32                                 <label for="name" class="form-label">Fair Name</label>
   33                                 <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required placeholder="e.g. Ekushey Mela 2026">
   34                             </div>
   35                         </div>
   36
   37                         <div class="row mb-3">
   38                             <div class="col-md-12">
   39                                 <label for="location" class="form-label">Location</label>
   40                                 <input type="text" name="location" id="location" class="form-control" value="{{ old('location') }}" required placeholder="e.g. Dhaka
      University Grounds">
   41                             </div>
   42                         </div>
   43
   44                         <div class="row mb-3">
   45                             <div class="col-md-6">
   46                                 <label for="start_date" class="form-label">Start Date</label>
   47                                 <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date') }}" required>
   48                             </div>
   49                             <div class="col-md-6">
   50                                 <label for="end_date" class="form-label">End Date</label>
   51                                 <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date') }}" required>
   52                             </div>
   53                         </div>
   54
   55                         <div class="row mb-4">
   56                             <div class="col-md-6">
   57                                 <label for="total_stalls" class="form-label">Total Stalls Capacity</label>
   58                                 <input type="number" name="total_stalls" id="total_stalls" class="form-control" value="{{ old('total_stalls') }}" required min="1">
   59                             </div>
   60                             <div class="col-md-6">
   61                                 <label for="default_limit" class="form-label">Daily Visitor Limit</label>
   62                                 <input type="number" name="default_limit" id="default_limit" class="form-control" value="{{ old('default_limit') }}" required min="1">
   63                             </div>
   64                         </div>
   65
   66                         <div class="d-grid">
   67                             <button type="submit" class="btn btn-primary btn-lg">Create Fair & Generate Days</button>
   68                         </div>
   69                     </form>
   70                 </div>
   71                 <div class="card-footer text-muted text-center small">
   72                     Member 1: Admin Panel & Stored Procedure Integration
   73                 </div>
   74             </div>
   75         </div>
   76     </div>
   77 </div>
   78 @endsection