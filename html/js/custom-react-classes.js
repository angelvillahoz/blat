class SpeciesScientificNameSelector extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      speciesShortName: 'dmel',
    };
  };
  handleChange = e => {
     this.setState({speciesShortName:e.target.value});
  };
  render() {
    return (
      <div className="SpeciesScientificNameSelector">
      <select 
        value={this.state.speciesShortName} 
        onChange={this.handleChange} 
      >
        <option value="aaeg">Aedes aegypti (aaeg)</option>
        <option value="agam">Anopheles gambiae (agam)</option>
        <option value="dmel">Drosophila melanogaster (dmel)</option>
        <option value="tcas">Tribolium castaneum (tcas)</option>
      </select>
      </div>        
    );
  }
}

class BlatForm extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      speciesShortName: 'dmel',
      identityPercentage: '95',
      sequence: '',
    };
  };
  handleFormSubmit = e => {
    e.preventDefault();
    axios({
      data: this.state,
      headers: { 'content-type': 'application/json' },
      method: 'post',
      url: API_PATH + '/search'
    })
    .then(function (response) {
      console.log(response)
      //this.setState({
      //  success: true
      //})
    })
    .catch(error => this.setState({ error: error.message }));
  };    
  render() {
    return ( 
      <div className="BlatForm">
        <p>BLAT server</p>
        <div>
          <form>
            <label>Species Scientific Name:&nbsp;</label>
            <SpeciesScientificNameSelector name="SpeciesScientificNameSelector" />
            <br />            
            <label>Identity Percentage:&nbsp;</label>
            <br />            
            <input type="text" 
                   id="identityPercentageId"
                   name="identityPercentage"
                   value={this.state.identityPercentage}
                   onChange={e => this.setState({ identityPercentage: e.target.value })}/>
            <label>%</label><br />
            <br />
            <label>Sequence:&nbsp;</label><br />
            <textarea id="sequenceId"
                      name="sequence"
                      rows={10}
                      cols={100}
                      value={this.state.sequence}
                      onChange={e => this.setState({ sequence: e.target.value })}></textarea><br />
            <br />
            <input type="submit"
                   value="Submit"
                   onClick={e => this.handleFormSubmit(e)} />
          </form>
        </div>
      </div>
    );
  }
}