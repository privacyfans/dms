
<table class="table table-bordered table-hover  mb-0 text-md-nowrap border">
        <tr>
            <!-- <th>No</th> -->
            <th>Question</th>
            <th>Answer</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($faq as $br)
        <tr>
            
            <td>{!! $br->question !!}</td>
            <td>{!! $br->answer !!}</td>
            
            
            <td>
                <form action="{{ route('faq.destroy',$br->id ) }}" method="POST">
   
                    <a class="btn btn-info" href="{{ route('faq.show',$br->id) }}">Show</a>
    
                    <a class="btn btn-primary" href="{{ route('faq.edit', $br->id) }}">Edit</a>
   
                    @csrf
                    @method('DELETE')
      
                    <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-danger" >Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
    <div id="pagination">
    {!! $faq->links() !!}
    </div>